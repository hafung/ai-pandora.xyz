<?php

namespace App\Services\Article\DTOs;


class ArticleMetadata
{
    public function __construct(
        public string $title,
        public string $description,
        public array $keywords,
        public array $mainFeatures
    ) {}
}
