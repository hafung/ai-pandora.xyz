<?php

namespace App\Services\Article\Contracts;


use App\Services\Article\DTOs\GenerationResult;

interface ArticleGeneratorInterface {
    public function setMainContent(string $content): self;
    public function generateArticle(): GenerationResult;
}
