<?php

namespace App\Services\AI;

use App\Services\AI\Params\AiChatParams;
use Exception;
use App\Services\AI\Adapters\OpenAiAdapter;
use App\Services\AI\Factories\AdapterFactory;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;
use InvalidArgumentException;
use Qiniu\Auth;
use Qiniu\Config;
use Qiniu\Storage\BucketManager;
use App\Services\AI\Contracts\AiAdapterInterface;

class AiService {


    protected ?AiAdapterInterface $aiAdapter = null;

    protected int $contextLimit = 38;
    protected string $contextCacheKey = 'ai_chat:context:%s';

    protected string $chatLockKey = 'ai_chat:lock:%s';

    const CONTEXT_TTL = 60 * 60;

    public function __construct(?AiAdapterInterface $aiAdapter = null) {
        if ($aiAdapter) {
            $this->aiAdapter = $aiAdapter;
        } else {
            $this->initLLMAdapterByProvider();
        }
    }

    public function setContextLimit(int $limit): AiService {
        $this->contextLimit = $limit;
        return $this;
    }


    public function initLLMAdapterByProvider($provider = '') {
        $provider = $provider ?: config('ai.default_llm_provider');
        switch ($provider) {
            case 'swoole':
                $this->aiAdapter = new SwooleAiAdapter();
                break;
            case 'omg':
            case 'v3':
            case 'together':
            case 'silicon':
                $this->aiAdapter = new OpenAiAdapter(
                    $provider,
                    config("ai.{$provider}_api_key"),
                    config("ai.{$provider}_api_host")
                );
                break;
            default:
                throw new InvalidArgumentException("Unsupported AI provider: {$provider}");
        }
    }

    public function resetServiceAdapterByProvider(string $provider): void {
        $this->initLLMAdapterByProvider($provider);
    }

    /**
     * @throws Exception
     */
    public function chat(AiChatParams $params, $stream = false, callable $callback = null): ?string {

        $massages = $this->combineMessages($params->getUserPrompt(), $params->getUser(), $params->getSystemPrompt(), $params->getUseContext());

        $params->setMessages($massages);

        return $this->aiAdapter->chat($params, $stream, $callback);

    }


    public function streamChat(AiChatParams $params, callable $callback = null): ?string {

        return $this->aiAdapter->chat($this->assembleMessages($params), true, $callback);
    }

    public function callAgentOnce($userPrompt, $opts = []): ?string {

        $model = $opts['model'] ?? AiConst::MODEL_GPT_4O;

        $aiChatParams = new AiChatParams([
            'model' => $model,
            'user_prompt' => $userPrompt,
            'use_context' => false,
            'temperature' => 0.7,
            'checkpoint' => AiConst::SWOOLE_MODELS_CHECKPOINTS[$model] ?? ''
        ]);

//        return $this->aiAdapter->chat($aiChatParams);
        return $this->chat($aiChatParams);
    }

    public function assembleMessages(AiChatParams $params): AiChatParams {

        $urls = $params->getImages();
        $prompt = $params->getUserPrompt();
        $sysPrompt = $params->getSystemPrompt();

        if (!empty($urls) && is_array($urls)) {
            // 兼容openai格式
            $userContent = [
                "role" => "user",
                "content" => [
                    ['type' => 'text', 'text' => $prompt]
                ]
            ];

            foreach ($urls as $index => $url) {
                $userContent['content'][] = [
                    'type' => 'image_url',
                    'image_url' => ['url' => $url]
                ];
            }
        } else {
            $userContent = [
                "role" => "user",
                "content" => $prompt
            ];
        }

        if ($params->getUseContext()) {

            $messages = $this->getContext($params->getUser());

            $sysPrompt && array_unshift($messages, ['role' => 'system', 'content' => $sysPrompt]);

            array_push($messages, $userContent);

        } else {
            if ($sysPrompt) {

                $messages = [
                    ["role" => "system", "content" => $sysPrompt],
                    $userContent
                ];
            } else {
                $messages = [
                    $userContent
                ];
            }

        }

        $params->setMessages($messages);

        return $params;
    }

    public function combineMessages(string $prompt, string $userFlag, string $sysPrompt = '', bool $getHistory = false): array {

        if ($getHistory) {

            $messages = $this->getContext($userFlag);

            $sysPrompt && array_unshift($messages, ["role" => "system", "content" => $sysPrompt]);

            array_push($messages, ["role" => "user", "content" => $prompt]);

            return $messages;
        } else {

            if ($sysPrompt) {
                return [
                    ["role" => "system", "content" => $sysPrompt],
                    ["role" => "user", "content" => $prompt]
                ];
            }
            return [
                ["role" => "user", "content" => $prompt],
            ];

        }
    }

    public function storeMessage($deviceId, $role, $content, $ifTrim = false, $ifSetExpired = false) {

        $key = sprintf($this->contextCacheKey, $deviceId);

        $len = Redis::connection()->rpush($key, [json_encode(["role" => $role, "content" => $content])]);

        if ($ifTrim) {
            $this->trimMessages($len, $key);
        }

        if ($ifSetExpired) {
            $this->setContextExpire($key);
        }

    }

    public function trimMessages($len, $key) {
        if ($len > $this->contextLimit) {
            Redis::connection()->lpop($key);
            $len--;
            if ($len % 2 != 0) {
                Redis::connection()->lpop($key);
            }
        }
    }

    public function setContextExpire($key) {
        Redis::connection()->expire($key, self::CONTEXT_TTL);
    }

    public function clearContext($deviceId) {
        $key = sprintf($this->contextCacheKey, $deviceId);
        Redis::connection()->del($key);
    }

    public function clearLockStatus($user) {
        $key = sprintf($this->chatLockKey, $user);
        Redis::connection()->del($key);
    }

    public function getContext($deviceId): array {

        $key = sprintf($this->contextCacheKey, $deviceId);

        $messages = Redis::connection()->lrange($key, 0, -1);

        return array_map(function ($message) {
            return json_decode($message, true);
        }, $messages);
    }

    public function outputStreamData($data, &$aiResponseTxt, $needFlush = true) {

//        if (config('app.env') === 'local') {
//            Log::info('outputStreamData' . $data);
//        }

        if ($data === '[DONE]') return;
        $chunk = json_decode($data, true);
        if ($this->aiAdapter->getProvider() === 'swoole') {

            if (is_array($chunk)) {
                $this->aiAdapter->setError('请稍后重试, swoole ai 发生异常: ' . $data);
                Log::error('swoole AI stream error: ' . $data);
                echo json_encode($chunk);
            } else {
                $aiResponseTxt .= $chunk;
                echo $chunk;
            }
        } else {
            // 如果前端已经兼容openai格式，直接echo 源数据，不在这里处理！
            if (isset($chunk['error'])) {
                echo $chunk['error']['message'];
            } else {
                $aiResponseTxt .= $chunk['choices'][0]['delta']['content'] ?? '';
                echo $chunk['choices'][0]['delta']['content'] ?? '';
            }
        }

    }

    public function handleStuffAfterAiResponse(AiChatParams $aiChatParams, $aiResponseTxt, $did) {

        if ($aiResponseTxt) {

            if ($aiChatParams->getUseContext()) {
                $this->storeMessage($did, 'user', $aiChatParams->getUserPrompt());
                $this->storeMessage($did, 'assistant', $aiResponseTxt, true, true);
            }
        }
    }

    public function storeMessages(AiChatParams $params, $aiResponseTxt) {

        $urls = $params->getImages();
        $prompt = $params->getUserPrompt();

        if ($urls) {
            $this->storeMessage($params->getUser(), 'user', [
                'app' => 'swoole-ai',
                'type' => 'chat-vision',
                'images' => $urls,
                'prompt' => $prompt,
                'image_detail' => 'low'
            ]);
        } else {
            $this->storeMessage($params->getUser(), 'user', $prompt);
        }

        $this->storeMessage($params->getUser(), 'assistant', $aiResponseTxt, true, true);
    }


    public function delFilesAfterDays($keyDayPairs) {

        $accessKey = config('filesystems.disks.qiniu.access_key');
        $secretKey = config('filesystems.disks.qiniu.secret_key');
        $bucket = config('filesystems.disks.qiniu.bucket');

        $auth = new Auth($accessKey, $secretKey);
        $config = new Config();
        $bucketManager = new BucketManager($auth, $config);

        // 每次最多不能超过1000个
        $ops = $bucketManager->buildBatchDeleteAfterDays($bucket, $keyDayPairs);

        list(, $err) = $bucketManager->batch($ops);

        if ($err) {
            if (is_array($err) || is_object($err)) {
                Log::error('delFilesAfterDays', $err);
            } else {
                Log::error('delFilesAfterDays' . $err);
            }
        }

    }


    public function getError() {
        return $this->aiAdapter->getError();
    }


    public function getAdapterProvider(): string {
        return $this->aiAdapter->getProvider();
    }


    public function getProviderByModelName(string $modelName): string {

        foreach (AiConst::MODEL_LIST as $model) {
            if ($modelName === $model['val']) {
                if (empty($model['providers'])) {
                    return '';
                }
                $randomIndex = array_rand($model['providers']);
                return $model['providers'][$randomIndex];
            }
        }

        return '';
    }



}
