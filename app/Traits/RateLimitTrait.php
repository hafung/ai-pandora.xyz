<?php

namespace App\Traits;

use Illuminate\Support\Facades\Cache;

trait RateLimitTrait {

    private int $searchLimit = 30;
    private int $progressLimit = 50;
    private int $ttl = 86400;
    private array $limitData = [
        'search' => 30,
        'progress' => 50,
        'translate' => 30,
        'poem' => 30,
        'ai_name' => 30,
        'optimize_question' => 30,
        'name-scoring' => 30,
        'emoji_translator' => 30,
        'alchemy-of-soul' => 30,
    ];

    public function isLimitExceeded(string $type, string $ip): bool {
        $count = $this->getCount($type, $ip);
        $limit = $this->getLimit($type);
        return $count >= $limit;
    }

    public function incrementCount(string $type, string $ip): void {
        $key = $this->getKey($type, $ip);
        $count = Cache::get($key, 0);
        Cache::put($key, $count + 1, $this->ttl);
    }

    private function getCount(string $type, string $ip): int {
        $key = $this->getKey($type, $ip);
        return Cache::get($key, 0);
    }

    private function getKey(string $type, string $ip): string {
        return "{$type}_count_{$ip}";
    }

    private function getLimit(string $type): int {
//        return $type === 'search' ? $this->searchLimit : $this->progressLimit;
        return $this->limitData[$type] ?? 30;
    }

}
