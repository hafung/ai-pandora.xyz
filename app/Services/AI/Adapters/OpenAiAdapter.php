<?php

namespace App\Services\AI\Adapters;

use App\Services\AI\Factories\AdapterFactory;
use Throwable;
use Exception;
use GuzzleHttp\Client;
use App\Library\SwooleAi\OpenAi;
use Illuminate\Support\Facades\Log;
use App\Services\AI\Params\AiChatParams;
use Illuminate\Support\Facades\Storage;
use App\Services\AI\Contracts\AiAdapterInterface;

class OpenAiAdapter implements AiAdapterInterface {

    protected $apiKey;
    protected $apiHost;
    private OpenAi $openAi;
    private string $provider;

//    public function __construct($apiKey = '', $host = '', $provider = '') {
//    public function __construct($provider = '', $apiKey = '', $host = '') {
    public function __construct($provider = 'openai', $apiKey = '', $host = '') {

        // todo only need provider in constructor, 唯一弊端就是和config绑定死了，不能灵活传参？ 但目前已经破坏了，深度绑定provider config了...

//        $this->apiKey = $apiKey ?: config('ai.openai_api_key');
        $this->apiKey = $apiKey ?: config("ai.{$provider}_api_key");

        $this->openAi = new OpenAi($this->apiKey);

//        $this->apiHost = $host ?: config('ai.openai_api_host');
        $this->apiHost = $host ?: config("ai.{$provider}_api_host");

        $this->provider = $provider;

        $this->openAi->setBaseURL($this->apiHost);
    }

    public function getModelNameForWriting(): string {
        return $this->getModelName('writing');
    }

    public function getModelNameForCoding(): string {
        return $this->getModelName('coding');
    }

    public function getModelNameForChat(): string {
        return $this->getModelName('chat');
    }

    public function getCheapestModel(): string {
        return $this->getModelName('cheapest');
    }

    public function getModelName(string $type) {
        return config("ai.{$this->provider}_llm_for_{$type}");
    }

    // getProvider
    public function getProvider(): string {
        return $this->provider;
    }

    public function getModels() {

        $jsonStr = $this->openAi->listModels();
        if (is_string($jsonStr)) {
            return json_decode($jsonStr, true);
        }

        return [];
    }


    public function getError(): string {
        return $this->openAi->getError();
    }

    /**
     * @param AiChatParams $params
     * @param bool $stream
     * @param callable|null $callback
     * @return string
     * @throws Exception
     */
    public function chat(AiChatParams $params, bool $stream = false, callable $callback = null): ?string {

        $bodyParams = [
            'model' => $params->getModel(),
            'messages' => $params->getMessages(),
            'temperature' => $params->getTemperature(),
            'stream' => $stream
        ];

//        if (config('app.env') === 'local') {
//            Log::info('open-ai-chat-params', $bodyParams);
//        }

        if ($stream && $callback) {
            return $this->openAi->chat($bodyParams, $callback);
        }

        if ($stream && !$callback) {
            throw new Exception('stream模式，需传入回调方法');
        }
        $rawResponse = $this->openAi->chat($bodyParams);

        if (is_string($rawResponse)) {
            $rawResponse = json_decode($rawResponse, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::error('open-ai-chat-error ' . $rawResponse);
                throw new Exception('OpenAI返回数据格式错误: NOT A JSON');
            }
        }

//        if (config('app.env') === 'local') {
//            Log::info('open-ai-chat-response', $rawResponse);
//        }

        if (isset($rawResponse['error'])) {
            Log::error('open-ai-chat-error', $rawResponse);
            throw new Exception($rawResponse['error']['message']);
        }

        // openai那边没有checkpoint参数
        $content = $rawResponse['choices'][0]['message']['content'] ?? '';

        if (!$content) {
            Log::error('open-ai-chat-error ', $rawResponse);
            throw new Exception($rawResponse['message'] ?? 'OpenAI返回数据格式错误: empty content');
        }

        return $content;
    }


    /**
     * ephone 加个: Standard 1024×1024 $0.04  1024×1792 1792×1024 $0.08;  HD 1024×1024 $0.08 1024×1792 1792×1024    $0.12
     * @throws Exception
     */
    public function generateImages(array $opt): array {

        if (isset($opt['aspect_ratio'])) {
            $opt['size'] = $this->calculateSizeFromAspectRatio($opt['aspect_ratio']);
        }

        // 将中转商的支持的、同时支持dalle格式的图片模型列出来， 比如 ephone =》 ['recraftv3',''],   common => ['dall-e-3']
        // if provider is ephone, model could choose: recraftv3(￥0.08/次)  flux.1.1-pro/flux-pro（￥0.12/次） flux-pro-1.1-ultra（￥0.18/次）  flux-dev（￥0.075）  ~~kling_image（￥0.04）~~ 异步
        // dall-e-3 standard ￥0.04/￥0.08   HD ￥0.08/￥0.12
        // v3 和 ephone 共用的画图model: flux-dev flux-pro flux.1.1-pro dall-e-3  -- v3不会长期用，完了就换ephone了...
        // todo 测试下 kling-v1  -- 异步的，用新的适配器吧
        // v3 换 flux-dev 报错，可能是因为 不支持 'response_format' => 'b64_json'  -- damn right!!
        $response = $this->openAi->image([
                'model' => $opt['model'] ?? 'flux-dev',
                'prompt' => $opt['prompt'],
                'size' => $opt['size'] ?? '1024x1024',
                'quality' => $opt['quality'] ?? 'standard',
                'n' => $opt['n'] ?? 1,
                'response_format' => 'url'
//                'response_format' => 'b64_json'
            ]);
        if (empty($response)) {
//            Log::error('open-ai-image-error', $response);
            return AdapterFactory::create(SiliconAiAdapter::class)->generateImages($opt);
//            throw new Exception('OpenAI返回数据格式错误');
        }

        $responseArr = json_decode($response, true);

        if (empty($responseArr['data'])) {
            return AdapterFactory::create(SiliconAiAdapter::class)->generateImages($opt);
//            throw new Exception('OpenAI返回数据格式错误');
        }

        return array_column($responseArr['data'], 'url') ?? []; // 直接给原始url，hk server不会被墙

//        return $this->processImages($responseArr['data']); // works too
//        return $this->processAndStoreImages($responseArr['data']);
    }

    private function calculateSizeFromAspectRatio(string $aspectRatio): string {

        $landscapeSize = '1792x1024'; // 横幅尺寸
        $portraitSize = '1024x1792';  // 竖向尺寸
        $squareSize = '1024x1024';  // 方形尺寸

        switch ($aspectRatio) {
            case 'ASPECT_16_9':
            case 'ASPECT_4_3':
            case 'ASPECT_3_2':
            case 'ASPECT_3_1':
            case 'ASPECT_16_10':
                return $landscapeSize;
            case 'ASPECT_10_16':
            case 'ASPECT_9_16':
            case 'ASPECT_3_4':
            case 'ASPECT_1_3':
            case 'ASPECT_2_3':
                return $portraitSize;
            default:
                return $squareSize;
        }

    }

    private function processAndStoreImages(array $data): array {

        $urls = [];
        $disk = Storage::disk('qiniu');
        foreach ($data as $index => $datum) {

            if (isset($datum['b64_json'])) {
                $imageData = base64_decode($datum['b64_json']);

                $fileName = 'ai_gen_img_' . time() . $index . '.png'; // todo dir & prefix for config
                $filePath = 'images/' . $fileName;

                if ($disk->put($filePath, $imageData)) {
//                    $url = $disk->url($filePath);
                    $url = $disk->downloadUrl($filePath, 'https');
                    $urls[] = $url;
                } else {
                    Log::error("Failed to save the image");
                }
            }
        }

        return $urls;
    }

    private function processImages(array $imageData): array {
        $results = [];

        foreach ($imageData as $index => $datum) {
            if (!isset($datum['b64_json'])) {
                Log::warning('Missing b64_json in image data', ['index' => $index]);
                continue;
            }

            $results[] = 'data:image/png;base64,' . $datum['b64_json'];
        }

        return $results;
    }

    // todo 语音转文本  /v1/audio/transcriptions


    // todo /v1/audio/translations -- 单纯音频翻译为英文


    // todo 目前写死查的v3，后续统一接口 --不好统一，很多三方没开放接口查询
//    public function getBalance() {
//
//        $accessToken = config('ai.v3_ai_access_token');
//
//        try {
//
//            $response = (new Client())->get($this->apiHost . '/api/user/self', [
//                'headers' => [
//                    'Authorization' => 'Bearer ' . $accessToken,
//                    'Content-Type' => 'application/json'
//                ]
//            ]);
//
//            $contents = json_decode($response->getBody()->getContents(), true);
//
//        } catch (\GuzzleHttp\Exception\ConnectException | Exception $e) {
//
//            return '获取V3余额失败:' . $e->getMessage();
//
//        }
//
//        if (!isset($contents['data']['quota'])) {
//            return $contents['message'] ?? '获取余额失败';
//        }
//
//        return $contents['data']['quota'] / 500000;  // $
//
//    }


}
