<?php

namespace App\Http\Livewire;

use App\Services\AI\AiConst;
use App\Services\AI\PromptTemplates;
use App\Traits\AiGenerationTrait;
use Livewire\Component;
use Illuminate\Support\Facades\Http;

class EmojiTranslator extends Component {
    use AiGenerationTrait;

    protected $rules = [
        'prompt' => 'required|min:2|max:50',
    ];
    protected $messages = [
        'prompt.required' => '请至少输入一句话。Please enter at least 2 characters.',
    ];


    protected function getAiType(): string {
        return 'emoji_translator';
    }

    protected function getPromptTemplate(): string {
        return PromptTemplates::EMOJI_TRANSLATOR;
    }

    protected function getModelTemperature(): float {
        return 0.7;
    }

    protected function getUseContext(): bool {
        return false;
    }

    protected function afterContentGenerated() {
    }

    protected function createUserPrompt(): string {

        return $this->replaceTemplateVariables($this->getPromptTemplate(), [
            '[USER_INPUT]' => $this->prompt,
            '[LANGUAGE]' => app()->getLocale()
        ]);
    }


    public function render() {
        return view('livewire.emoji-translator')->layout('layouts.tools-live', [
            'title' => __('seo.emoji_translator.title'),
            'description' => __('seo.emoji_translator.description'),
            'keywords' => __('seo.emoji_translator.keywords'),
        ]);
    }



    protected function getSpecificModelName(): string {
        return AiConst::MODEL_GPT_4O;
    }

    protected function beforeGenerate() {
        $this->generatedContent = '';
    }
}
