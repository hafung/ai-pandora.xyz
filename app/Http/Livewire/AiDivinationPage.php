<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Services\AI\AiConst;
use App\Traits\AiGenerationTrait;
use Illuminate\Support\Facades\Log;
use App\Services\AI\PromptTemplates;

class AiDivinationPage extends Component {

    use AiGenerationTrait;

    protected array $messages = [
        'prompt.required' => '请输入你的想法',
    ];
    protected array $rules = [
        'prompt' => 'required|min:5',
    ];

    public function render() {
        return view('livewire.ai-divination-page')->layout('layouts.tools-live', [
            'title' => __('seo.ai_divination.title'),
            'description' => __('seo.ai_divination.description'),
            'keywords' => __('seo.ai_divination.keywords'),
        ]);
    }


    // todo rename to getAiAppType
    protected function getAiType(): string {
        return 'ai_divination';
    }

    protected function getPromptTemplate(): string {
        return PromptTemplates::AiDivination;
    }

    protected function createUserPrompt(): string {
        return $this->replaceSingleVariableInTemplate($this->getPromptTemplate());
    }

    protected function afterContentGenerated() {
    }

    protected function getUseContext(): bool {
        return false;
    }

    protected function getModelTemperature(): float {
//        return 0.8;
        return 0.9;
    }

    protected function getSpecificModelName(): string {
        return AiConst::MODEL_GPT_4O;
//        return AiConst::MODEL_GEMINI_1_5_FLASH_LATEST;
    }

    protected function beforeGenerate() {
        $this->generatedContent = '';
    }

}
