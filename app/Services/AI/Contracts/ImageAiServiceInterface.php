<?php

namespace App\Services\AI\Contracts;


interface ImageAiServiceInterface {

    /**
     * Submit a task to create PPT.
     *
     * @param string $prompt
     * @param array $opt
     * @return array [$url1, $url2, ...]
     */
    public function generateImages(string $prompt, array $opt): array;


}
