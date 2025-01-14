<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AiFunctionLimiter {
    public function handle(Request $request, Closure $next) {
        $ip = $request->ip();
        $key = 'search_count_' . $ip;
        $limit = 100; // 设置每个 IP 的搜索次数限制
        $ttl = 60 * 60; // 1小时后重置计数器

        $count = Cache::get($key, 0);

        if ($count >= $limit) {
            return response()->json(['error' => '搜索次数已达到限制，请稍后再试。'], 429);
        }

        Cache::put($key, $count + 1, $ttl);

        return $next($request);
    }
}
