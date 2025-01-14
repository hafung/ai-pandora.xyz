<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return Response|RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
//        $locales = $request->getLanguages();
//        Log::debug('$locales', $locales); // ["zh_CN","zh","en","en_GB","en_US","zh_TW","ja"]
//        $locale = $request->getPreferredLanguage(config('app.available_locales')); // 传入的话，只会返回第一个
//        Log::debug($locale); // en
//        App::setLocale($locale);
//        return $next($request);

        ////

//        $locale = $request->segment(1);
//        if ($locale && in_array($locale, array_keys(config('app.available_locales')))) {
//            app()->setLocale($locale);
//        }
//        return $next($request);


        ////

//        $browserLocales = $request->getLanguages();
//        $availableLocales = config('app.available_locales');

//        Log::debug('Browser Locales', $browserLocales);
//        Log::debug('Available Locales', $availableLocales);

//        $locale = $this->findBestMatchingLocale($browserLocales, $availableLocales);
        $locale = $this->findBestMatchingLocale($request->getLanguages(), config('app.available_locales'));

        App::setLocale($locale);
//        Log::debug('Selected Locale', [$locale]);

        return $next($request);
    }

    /**
     * 查找最佳匹配的语言。
     *
     * @param array $browserLocales
     * @param array $availableLocales
     * @return string
     */
    private function findBestMatchingLocale(array $browserLocales, array $availableLocales)
    {
        $defaultLocale = config('app.fallback_locale', 'en');

        foreach ($browserLocales as $browserLocale) {
            $normalizedBrowserLocale = $this->normalizeLocale($browserLocale);

            foreach ($availableLocales as $availableLocale) {
                $normalizedAvailableLocale = $this->normalizeLocale($availableLocale);

                // 检查完全匹配
                if ($normalizedBrowserLocale === $normalizedAvailableLocale) {
                    return $availableLocale;
                }

                // 检查语言匹配（不考虑地区）
                if (strpos($normalizedBrowserLocale, $normalizedAvailableLocale) === 0) {
                    return $availableLocale;
                }
            }
        }

        return $defaultLocale;
    }

    /**
     * 标准化语言代码。
     *
     * @param string $locale
     * @return string
     */
    private function normalizeLocale(string $locale): string
    {
        // 转换为小写
        $locale = strtolower($locale);

        // 移除可能的 UTF-8 后缀
        $locale = preg_replace('/-?utf-?8$/i', '', $locale);

        // 标准化分隔符（使用连字符）
        $locale = str_replace('_', '-', $locale);

        // 处理特殊情况：中文
        if (in_array($locale, ['zh-cn', 'zh-hans', 'zh-hans-cn'])) {
//            return 'zh-cn';
            return 'zh-CN';
        }
        if (in_array($locale, ['zh-tw', 'zh-hant', 'zh-hant-tw'])) {
//            return 'zh-tw';
            return 'zh-TW';
        }
        if ($locale === 'zh') {
//            return 'zh-cn'; // 默认简体中文，可以根据需求调整
            return 'zh-CN'; // 默认简体中文，可以根据需求调整
        }

        return $locale;
    }
}
