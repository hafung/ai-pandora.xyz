<?php

namespace App\Services\Article;

use App\Services\AI\AiService;

class ArticleGeneratorFactory
{

    public static function createGenerator(string $type): BaseArticleGenerator
    {
        $aiService = app(AiService::class);

        return match ($type) {
             'github' => new GithubArticleGenerator($aiService),
            'common','news','micro_fiction','urban_romance','toxic_chicken_soup' => new CommonArticleGenerator($aiService),
            default => throw new \InvalidArgumentException("Unsupported article generator type: {$type}"),
        };
    }
}
