<?php

namespace App\Services\AI\Contracts;

//use App\Services\AI\ImageParams;
use App\Services\AI\Params\ImageParams;

interface AsyncImageAiServiceInterface {

    public function submitTask(ImageParams $opt): array;
    public function getResult(array $keys): array;

}
