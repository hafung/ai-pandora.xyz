<?php

namespace App\Traits;

use App\Services\AI\AiConst;
use App\Services\AI\AiService;
use App\Services\AI\Params\AiChatParams;
use Exception;

trait ArticleTrait {

    public function promptTemplateForCreateTitleAndDigest($articleContent): string {
        return "请从以下文章中提取一个极致引人注目到浓烈的“标题党”程度的标题和一个不超过10个字的摘要。

文章内容:
{$articleContent}

请严格按照以下格式返回：

标题: <提取的标题>
摘要: <提取的摘要>";
    }

    /**
     * @throws Exception
     */
    public function createTitleAndDigest($content, $model = AiConst::MODEL_GPT_4O, $temperature = 0.7) {

        /* @var AiService $aiService */
        $aiService = app(AiService::class);

        $targetProvider = $aiService->getProviderByModelName($model);

        if ($aiService->getAdapterProvider() !== $targetProvider) {
            $aiService->resetServiceAdapterByProvider($targetProvider);
        }

        $articleSummary = $aiService->chat(new AiChatParams([
            'model' => $model,
            'user_prompt' => $this->promptTemplateForCreateTitleAndDigest($content),
            'use_context' => false,
            'temperature' => $temperature,
            'checkpoint' => AiConst::SWOOLE_MODELS_CHECKPOINTS[$model] ?? ''
        ]));

        preg_match('/标题:\s*(.+)\s*摘要:\s*(.+)/s', $articleSummary, $matches);

        if ($matches) {
            $title = trim($matches[1]);
            $digest = trim($matches[2]);
        } else {
            $title = "史上最详细的发财攻略";
            $digest = "发财宝典💴💴💴";
        }

        return ['title' => $title, 'digest' => $digest];
    }

}
