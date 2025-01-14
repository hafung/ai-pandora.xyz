<?php

namespace App\Traits;

use Throwable;
use App\Exceptions\DailyLimitExceededException;
use App\Exceptions\SensitiveWordException;
use App\Services\AI\AiConst;
use App\Services\AI\AiService;
use App\Services\AI\Factories\AiAdapterFactory;
use App\Services\AI\Params\AiChatParams;
use App\Utils\SensitiveWordFilter;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

/**
 * @mixin Component
 */
trait AiGenerationTrait {

    use RateLimitTrait;

    public string $prompt = '';
    public string $generatedContent = '';
    public float $modelTemperature = 0.7;
    public string $aiType;
    public string $promptTemplate;

    abstract protected function getAiType(): string;

    abstract protected function getPromptTemplate(): string;

    abstract protected function getSpecificModelName(): string;

    abstract protected function createUserPrompt(): string;

    abstract protected function afterContentGenerated();

    abstract protected function beforeGenerate();

    abstract protected function getUseContext(): bool;

    abstract protected function getModelTemperature(): float;

    public function mount() {
        $this->aiType = $this->getAiType();
        $this->promptTemplate = $this->getPromptTemplate();
        $this->modelTemperature = $this->getModelTemperature();
    }

    public function replaceSingleVariableInTemplate($template, $replaceFlag = '%s') {
        return str_replace($replaceFlag, $this->prompt, $template);
    }

    public function replaceTemplateVariables($template, $replacements): string {
        return strtr($template, $replacements);
    }

    public function generate() {
        try {
            $ip = request()->ip();
            if ($this->isLimitExceeded($this->aiType, $ip)) {
                throw new DailyLimitExceededException(__("You've reached today's usage limit. See you tomorrow!"));
            }

            // todo 白名单
            if ((new SensitiveWordFilter)->containsSensitiveWords($this->prompt)) {
                throw new SensitiveWordException(__("[Detected sensitive words] 😭 You're so brave, do you have a death wish?! 😰"));
            }

            $this->validate();

            $userPrompt = $this->createUserPrompt();

            // todo beforeGenerate new hook！
            $this->beforeGenerate();

            $model = $this->getSpecificModelName();
            $targetProvider = $this->getProviderByModelName($model);
            // todo 如果没有匹配的模型，则使用默认模型和provider
            $aiService = new AiService(AiAdapterFactory::getInstance()->getAdapter($targetProvider));

            $aiChatParams = new AiChatParams([
                'model' => $model,
                'user' => request()->cookie('browser_fingerprint'),
                'user_prompt' => $userPrompt,
                'use_context' => $this->getUseContext(),
                'temperature' => $this->modelTemperature,
                'checkpoint' => AiConst::SWOOLE_MODELS_CHECKPOINTS[$model] ?? ''
            ]);

            $this->generatedContent = $aiService->chat($aiChatParams) ?: __("You're doing that too often. Please try again later.");

            $this->afterContentGenerated();

        } catch (ValidationException $e) {
            $this->emit('showToast', $e->validator->errors()->first(), 'error', 2500); // works，fuck！
        } catch (DailyLimitExceededException $e) {
            $this->emit('showToast', $e->getMessage(), 'warning', 3000);
        } catch (SensitiveWordException $e) {
            $this->emit('showToast', $e->getMessage(), 'error', 3000);
        } catch (Throwable $e) {
            Log::error('ai_generate: ' . $e->getMessage());
            $this->emit('showToast', 'something is wrong', 'error', 2500);
        }
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
