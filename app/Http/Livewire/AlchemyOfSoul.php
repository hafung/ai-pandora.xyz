<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Services\AI\AiConst;
use App\Services\AI\PromptTemplates;
use App\Traits\AiGenerationTrait;

class AlchemyOfSoul extends Component {

    use AiGenerationTrait;

    protected array $rules = [
        'prompt' => 'required|min:2|max:50',
    ];

    public function messages() {
        return [
            'prompt.required' => __('Let me know the confusion you have'),
        ];
    }

    public function render() {
        return view('livewire.alchemy-of-soul')->extends('layouts.tools-live', [
            'title' => __('seo.alchemy_of_soul.title'),
            'description' => __('seo.alchemy_of_soul.description'),
            'keywords' => __('seo.alchemy_of_soul.keywords'),
        ]);
    }

    /**
     * @return string
     */
    protected function getAiType(): string {
        return 'alchemy-of-soul';
    }

    /**
     * @return string
     */
    protected function getPromptTemplate(): string {
        return PromptTemplates::WISDOM_GENERATOR;
    }

    /**
     * @return string
     */
//    protected function getModelType(): string {
//        return 'writing';
//    }

    /**
     * @return string
     */
    protected function createUserPrompt(): string {
        $replacements = [
            '[USER_INPUT]' => $this->prompt,
        ];
        return $this->replaceTemplateVariables($this->getPromptTemplate(), $replacements);
    }


    protected function afterContentGenerated() {
    }

    /**
     * @return bool
     */
    protected function getUseContext(): bool {
        return false;
    }

    /**
     * @return float
     */
    protected function getModelTemperature(): float {
//        return 0.7;
        return 0.8;
    }

    protected function getSpecificModelName(): string {
//        return AiConst::MODEL_GEMINI_1_5_FLASH_LATEST;
        return AiConst::MODEL_GPT_4O;
    }

    protected function beforeGenerate() {
        $this->generatedContent = '';
    }
}
