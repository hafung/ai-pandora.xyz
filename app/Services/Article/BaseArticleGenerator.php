<?php

namespace App\Services\Article;

use App\Services\AI\AiService;
use App\Services\AI\Params\AiChatParams;
use App\Services\AI\AiConst;
use App\Services\Article\Contracts\ArticleGeneratorInterface;
use App\Services\Article\DTOs\ArticleMetadata;
use App\Services\Article\DTOs\GenerationResult;
use Exception;

abstract class BaseArticleGenerator implements ArticleGeneratorInterface
{
    protected ArticleMetadata $metadata;
    protected string $responseFormat = 'markdown';
    protected string $aiModel = AiConst::MODEL_GPT_4O;
    protected AiService $aiService;

    public function __construct(AiService $aiService)
    {
        $this->aiService = $aiService;
        $this->metadata = new ArticleMetadata('', '', [], []);
    }

    public function setResponseFormat($format = 'markdown'): self
    {
        $this->responseFormat = $format;
        return $this;
    }

    public function setAiModel($aiModel) {
        $this->aiModel = $aiModel;
    }

    public function generateArticle(): GenerationResult
    {
        try {

            $targetProvider = $this->aiService->getProviderByModelName($this->aiModel);

            if ($this->aiService->getAdapterProvider() !== $targetProvider) {
                $this->aiService->resetServiceAdapterByProvider($targetProvider);
            }

            $article = $this->aiService->chat(new AiChatParams([
                'model' => $this->aiModel,
                'user_prompt' => $this->generatePrompt(),
                'use_context' => false
            ]));

            return new GenerationResult(
                success: true,
                article: $article,
                metadata: $this->metadata
            );
        } catch (Exception $e) {
            return new GenerationResult(
                success: false,
                error: $e->getMessage()
            );
        }
    }

    abstract protected function generatePrompt(): string;
}
