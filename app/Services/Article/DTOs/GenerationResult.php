<?php

namespace App\Services\Article\DTOs;

class GenerationResult
{
    public function __construct(
        public  bool $success,
        public  ?string $article = null,
        public  ?string $error = null,
        public  ?ArticleMetadata $metadata = null
    ) {}
}
