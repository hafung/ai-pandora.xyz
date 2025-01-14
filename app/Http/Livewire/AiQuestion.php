<?php

namespace App\Http\Livewire;

use App\Services\AI\AiConst;
use App\Services\AI\AiService;
use App\Services\AI\Params\AiChatParams;
use App\Services\AI\PromptTemplates;
use App\Traits\RateLimitTrait;
use App\Utils\SensitiveWordFilter;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Throwable;

class AiQuestion extends Component
{

    use RateLimitTrait;

//    public $userInput;
    public $question;
    public $aiResponse = '';
    public $isLoading;
    private $modelTemperature = 0.7;
    public string $browserFingerprint;

    protected array $rules = [
        'question' => 'required|min:1|max:512',
    ];

    public function mount() {
        $this->browserFingerprint = request()->cookie('browser_fingerprint', 'test');
        $this->isLoading = false;
    }
    public function sendMessage()
    {

        try {

            $ip = request()->ip();
            if ($this->isLimitExceeded('optimize_question', $ip)) {
                throw new \Exception('使用次数已达到今日上限，明天再来吧');
            }
            $this->validate();

            if ((new SensitiveWordFilter)->containsSensitiveWords($this->question)) {
                throw new \Exception('⚠[检测到敏感词] 😭 好勇啊你，不要命了你！😰');
            }

            $this->isLoading = true;

            $userPrompt = str_replace('"%s"', $this->question, PromptTemplates::GET_BETTER_QUESTION);

            $aiService = new AiService();

//            $model = $aiService->getModelName('writing');
            $model = AiConst::MODEL_GPT_4O;

            $aiChatParams = new AiChatParams([
                'model' => $model,
                'user' => $this->browserFingerprint,
                'user_prompt' => $userPrompt,
                'use_context' => false,
                'temperature' => $this->modelTemperature,
                'checkpoint' => AiConst::SWOOLE_MODELS_CHECKPOINTS[$model] ?? ''
            ]);

            $this->aiResponse = $aiService->chat($aiChatParams) ?: '操作太频繁啦，请稍后重试。';


            $this->isLoading = false;

        } catch (ValidationException $e) {
            $this->aiResponse = $e->validator->errors()->first();
            $this->isLoading = false;
        } catch (Throwable $e) {
            $this->aiResponse = $e->getMessage();
            $this->isLoading = false;
        }
    }

    public function render()
    {
        return view('livewire.ai-question')->layout('layouts.tools-live',[
            'title' => __('seo.ai_question.title'),
            'description' => __('seo.ai_question.description'),
            'keywords' => __('seo.ai_question.keywords')
        ]);
    }
}
