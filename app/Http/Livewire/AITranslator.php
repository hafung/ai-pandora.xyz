<?php

namespace App\Http\Livewire;

use App\Services\AI\AiConst;
use App\Services\AI\AiService;
use App\Services\AI\Factories\AiAdapterFactory;
use App\Services\AI\Params\AiChatParams;
use App\Services\AI\PromptTemplates;
use App\Traits\RateLimitTrait;
use App\Utils\SensitiveWordFilter;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Throwable;

class AITranslator extends Component {

    use RateLimitTrait;

    public string $inputText = '';
    public string $translatedText = '';
    public bool $isTranslating = false;
    /**
     * @var mixed
     */
    private $modelTemperature = 0.7;
    /**
     * @var mixed
     */
    public $browserFingerprint;

    protected array $rules = [
        'inputText' => 'required|min:1|max:2048',
    ];

    public function mount() {
        $this->browserFingerprint = request()->cookie('browser_fingerprint', 'test');

        $this->isTranslating = false;
    }

    public function translate() {

        try {

            $ip = request()->ip();
            if ($this->isLimitExceeded('translate', $ip)) {
                throw new \Exception('次数已达到今日上限，明天再来吧~');
            }

            $this->isTranslating = true;

            $this->validate();

            $userPrompt = str_replace('"%s"', $this->inputText, PromptTemplates::TRANSLATION_AUTOMATIC_PROMPT);

            $aiService = new AiService();

            $model = AiConst::MODEL_GPT_4O;
            $targetProvider = $aiService->getProviderByModelName($model);
            if ($aiService->getAdapterProvider() !== $targetProvider) {
                $aiService->resetServiceAdapterByProvider($targetProvider);
            }

            $aiChatParams = new AiChatParams([
                'model' => $model,
                'user' => $this->browserFingerprint,
                'user_prompt' => $userPrompt,
                'use_context' => false,
                'temperature' => $this->modelTemperature,
                'checkpoint' => AiConst::SWOOLE_MODELS_CHECKPOINTS[$model] ?? ''
            ]);

            $this->translatedText = $aiService->chat($aiChatParams) ?: '操作太频繁啦，请稍后重试。';

            $this->isTranslating = false;

        } catch (ValidationException $e) {
            $this->translatedText = $e->validator->errors()->first();
            $this->isTranslating = false;
        } catch (Throwable $e) {
            $this->translatedText = $e->getMessage();
            $this->isTranslating = false;
        }

    }

    public function render() {
        return view('livewire.a-i-translator')->layout('layouts.tools-live',[
            'title' => __('seo.ai_translator.title'),
            'description' => __('seo.ai_translator.description'),
            'keywords' => __('seo.ai_translator.keywords')
        ]);
    }
}
