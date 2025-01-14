<?php

namespace App\Services\AI\Contracts;


use App\Services\AI\ImageParams;

// async & for wujie
interface ImageAiServiceI {

//    public function submitTask(string $prompt, array $opt = []): array;
    public function submitTask(string $prompt, ImageParams $opt): array;
    public function getResult(array $keys): array;

}
