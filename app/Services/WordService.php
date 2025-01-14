<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
//use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class WordService {

    private const WORD_FILE_PATH = 'data/eng_words.txt';


    private const CACHE_MODIFIED_KEY = 'word_service_words_modified';
    private const CACHE_KEY = 'word_service_words';
    private const CACHE_EXPIRATION_MINUTES = 600;

    public function initializeCache(): void {
        $this->loadWordsToCache();
    }


    public function getRandomWord(): string {
        $words = $this->getWords();

        if (empty($words)) {
            throw new \RuntimeException('No words available');
        }

        return $words[array_rand($words)];
    }

    private function getWords(): array {
        return Cache::remember(self::CACHE_KEY, self::CACHE_EXPIRATION_MINUTES * 60, function () {
            return $this->loadWordsToCache();
        });
    }

    private function loadWordsToCache(bool $force = false): array {

        $filePath = resource_path(self::WORD_FILE_PATH);
        $currentModified = File::lastModified($filePath);

        $cachedModified = Cache::get(self::CACHE_MODIFIED_KEY);

        if ($force || !$cachedModified || $cachedModified < $currentModified) {
            $content = File::get($filePath);
            $words = array_filter(explode("\n", $content), 'trim');

            Cache::put(self::CACHE_KEY, $words, self::CACHE_EXPIRATION_MINUTES * 60);
            Cache::put(self::CACHE_MODIFIED_KEY, $currentModified, self::CACHE_EXPIRATION_MINUTES * 60);

            return $words;
        }

        return Cache::get(self::CACHE_KEY, []);
    }

}
