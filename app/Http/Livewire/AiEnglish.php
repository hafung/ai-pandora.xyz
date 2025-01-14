<?php

namespace App\Http\Livewire;

use Exception;
use Throwable;
use Livewire\Component;
use App\Services\AI\AiConst;
use App\Services\WordService;
use App\Services\AI\AiService;
use App\Utils\SensitiveWordFilter;
use Illuminate\Support\Facades\Log;
use App\Services\AI\PromptTemplates;
use Illuminate\Support\Facades\Cache;
use App\Services\AI\Params\AiChatParams;
use Illuminate\Validation\ValidationException;

class AiEnglish extends Component {

    public string $mode = 'search';
    public string $lastMode;
    public string $searchQuery = '';
    public array $svgCards = [];
    public bool $loading = false;
    public string $errorMessage = '';
    protected $listeners = ['setUniqueId'];
    public string $uniqueId;
    private int $searchLimit = 30;
    private int $progressLimit = 50;
    private int $ttl = 86400;
    private float $ModelTemperature = 0.7;

    protected array $rules = [
        'searchQuery' => 'required|min:1|max:128',
    ];

    public function boot() {
        // Log::debug('boot call');
    }

    public function mount() {
        // 没在组件申明的(wire.model)，需要在这里初始化
        $this->lastMode = $this->mode;
    }

    public function render() {
        return view('livewire.ai-english')->layout('layouts.eng-livewire');
    }

    public function setUniqueId($userId) {
        $this->uniqueId = $userId;
    }

    public function updatedMode($value) {
        // 在模式更新之后，可以在这里添加逻辑来恢复或更新状态
        if (!empty($this->svgCards[$value])) {
            $this->emit('generationStarted');
        }
    }

    public function setMode($newMode) {
        $this->lastMode = $this->mode;
        $this->mode = $newMode;
    }

    public function isLimitExceeded(string $type, string $ip): bool {
        $count = $this->getCount($type, $ip);
        $limit = $this->getLimit($type);
        return $count >= $limit;
    }

    public function incrementCount(string $type, string $ip): void {
        $key = $this->getKey($type, $ip);
        $count = Cache::get($key, 0);
        Cache::put($key, $count + 1, $this->ttl);
    }

    private function getCount(string $type, string $ip): int {
        $key = $this->getKey($type, $ip);
        return Cache::get($key, 0);
    }

    private function getKey(string $type, string $ip): string {
        return "{$type}_count_{$ip}";
    }

    private function getLimit(string $type): int {
        return $type === 'search' ? $this->searchLimit : $this->progressLimit;
    }

    public function search() {

        $this->resetError();

        $ip = request()->ip();
        try {

            if ($this->isLimitExceeded('search', $ip)) {
                throw new Exception('查询次数已达到限制，请稍后再试。');
            }

            $this->loading = true;
            $this->validate();

            if ((new SensitiveWordFilter)->containsSensitiveWords($this->searchQuery)) {
                throw new Exception('[检测到敏感词] 😭 好勇啊你，不要命了你！😰');
            }

            $this->emit('generationStarted');

            $this->svgCards[$this->mode] = $this->explainQueryToEnglishSvgCard();

            $this->loading = false;
            $this->emit('svgGenerated', [
                'mode' => $this->mode,
            ]);

            $this->incrementCount('search', $ip);

        } catch (ValidationException $e) {
            $this->addError('errorMessage', $e->validator->errors()->first());
        } catch (Throwable $e) {
            $this->addError('errorMessage', $e->getMessage());
        }

    }

    private function resetError() {
        $this->errorMessage = '';
    }

    public function progress() {

        $this->resetError();

        $ip = request()->ip();

        if ($this->isLimitExceeded('progress', $ip)) {
            $this->emit('showModal', '', '今日使用次数已耗尽，休息一下，明天继续学习哈~');
            return;
        }

        $this->loading = true;
        $this->emit('generationStarted');


        $resSvg = $this->getRandomEnglishSvgCard();
        if (!$resSvg) {
            $this->emit('showModal', '', '操作太猛了哈，别点太快，哥哥姐姐们 🙁');
        } else {
            $this->svgCards[$this->mode] = $resSvg;
        }

        $this->loading = false;

        $this->incrementCount('progress', $ip);

        $this->emit('svgGenerated', [
            'mode' => $this->mode,
        ]);
    }

    private function getAiResponse(string $prompt, string $word): string {
        // todo no need AiService, call adapter directly! it's more efficient, more fine-grained control.
        $aiService = new AiService();

//        $model = $aiService->getModelName('writing');
//        $model = AiConst::MODEL_GEMINI_1_5_FLASH_LATEST;
        $model = AiConst::MODEL_GPT_4O;
//        $targetProvider = $aiService->getProviderByModelName($model);
//        if ($aiService->getAdapterProvider() !== $targetProvider) {
//            $aiService->resetServiceAdapterByProvider($targetProvider);
//        }

        $aiChatParams = new AiChatParams([
            'model' => $model,
            'user' => $this->uniqueId,
            'user_prompt' => sprintf($prompt, $word),
            'use_context' => false,
            'temperature' => $this->ModelTemperature,
            'checkpoint' => AiConst::SWOOLE_MODELS_CHECKPOINTS[$model] ?? ''
        ]);

        return $aiService->chat($aiChatParams);
    }

    private function getRandomEnglishSvgCard(): string {
        $word = (new WordService)->getRandomWord();
        return $this->getAiResponse(PromptTemplates::RANDOM_ENGLISH_EXPLANATION, $word);
    }

    private function explainQueryToEnglishSvgCard(): string {
        return $this->getAiResponse(PromptTemplates::EXPLAIN_USER_INPUT_TO_ENGLISH, $this->searchQuery);
    }

}
