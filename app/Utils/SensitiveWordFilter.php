<?php

namespace App\Utils;

use Illuminate\Support\Facades\Cache;

class SensitiveWordFilter {

    private $pattern;

    public function __construct() {
        $this->pattern = $this->buildPattern();
    }

    private function buildPattern() {
        return Cache::remember('sensitive_words_pattern', 24 * 60 * 60, function () {
            $words = $this->loadSensitiveWords();
            $escapedWords = array_map(function ($word) {
                return preg_quote($word, '/');
            }, $words);
            return '/(' . implode('|', $escapedWords) . ')/u';
        });
    }

    private function loadSensitiveWords() {
        $filePath = resource_path('data/sensitive_words.txt');
        return file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    }

    public function containsSensitiveWords($text) {
        return preg_match($this->pattern, $text) === 1;
    }
}
