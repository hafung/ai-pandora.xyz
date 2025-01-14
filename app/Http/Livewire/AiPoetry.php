<?php

namespace App\Http\Livewire;

use App\Services\AI\AiService;
use Livewire\Component;
use App\Services\AI\AiConst;
use App\Traits\AiGenerationTrait;
use App\Services\AI\PromptTemplates;

class AiPoetry extends Component {

    use AiGenerationTrait;
    protected array $rules = [
        'prompt' => 'required|min:2|max:30',
    ];
    protected array $messages = [
        'prompt.required' => '请输入你的创作主题',
        'prompt.min' => '请至少输入2个字符',
        'prompt.max' => '最多可30个字符',
    ];

    protected function afterContentGenerated()
    {

    }

    protected function getModelTemperature(): float {
        return 0.7;
    }

    protected function getUseContext(): bool {
        return false;
    }

    protected function getAiType(): string {
        return 'poem';
    }

    protected function getPromptTemplate(): string {
        if (str_starts_with(app()->getLocale(), 'zh')) {
            return PromptTemplates::MODERN_CHINESE_POEM;
        }
        return PromptTemplates::MODERN_POEM;
    }

//    protected function getModelType(): string
//    {
//        return 'writing';
//    }

    protected function createUserPrompt(): string
    {
        return $this->replaceSingleVariableInTemplate($this->getPromptTemplate());
    }


    public function render() {

        return view('livewire.ai-poetry')
            ->layout('layouts.tools-live', [
                'title' => __('seo.ai_poetry.title'),
                'description' => __('seo.ai_poetry.description'),
                'keywords' => __('seo.ai_poetry.keywords'),
            ]);
    }

    protected function getSpecificModelName(): string {
        // TODO: Implement getSpecificModelName() method.
//        return AiConst::MODEL_GEMINI_1_5_FLASH_LATEST;
        return AiConst::MODEL_GPT_4O;
    }
    protected function beforeGenerate() {
        // TODO: Implement beforeGenerate() method.
        $this->generatedContent = '';
    }
}
