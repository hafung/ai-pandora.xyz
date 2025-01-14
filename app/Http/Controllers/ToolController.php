<?php

namespace App\Http\Controllers;


class ToolController {

    public function index($category = 'recommend') {

        $categories = [
            'recommend' => __('Recommended'),
            'ai' => __('messages.categories.ai'),
            'text' => __('messages.categories.text'),
            'dev' => __('messages.categories.dev'),
            'creative' => __('messages.categories.creative'),
            'marketing' => __('messages.categories.marketing'),
            'productivity' => __('messages.categories.productivity'),
        ];

        $tools = [
            [
                'name' => __('messages.tools.ai_translator.title'),
                'description' => __('messages.tools.ai_translator.description'),
                'route' => 'ai-translator',
                'icon' => 'M3 5h12m-12 5h12m-12 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129',
                'belong' => ['recommend', 'ai', 'productivity'],
                'order' => ['recommend' => 1, 'ai' => 1, 'productivity' => 1],
                'visible' => true,
            ],
            [
                'name' => __('messages.tools.ai_poetry.title'),
                'description' => __('messages.tools.ai_poetry.description'),
                'route' => 'ai-poetry',
                'icon' => 'M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z',
                'belong' => ['recommend', 'ai', 'creative'],
                'order' => ['recommend' => 4, 'ai' => 3, 'creative' => 2],
                'visible' => true,
            ],
            [
                'name' => __('messages.tools.ai_question.title'),
                'description' => __('messages.tools.ai_question.description'),
                'route' => 'ai-question',
                'icon' => 'M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
                'belong' => ['ai'],
                'order' => ['ai' => 9],
                'visible' => true,
            ],
            [
                'name' => __('messages.tools.json_beautify.title'),
                'description' => __('messages.tools.json_beautify.description'),
                'route' => 'tools.json-beautifier',
                'icon' => 'M4 3a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2V5a2 2 0 00-2-2H4zm2 3a1 1 0 011-1h10a1 1 0 110 2H7a1 1 0 01-1-1zm0 4a1 1 0 011-1h10a1 1 0 110 2H7a1 1 0 01-1-1zm0 4a1 1 0 011-1h4a1 1 0 110 2H7a1 1 0 01-1-1z',
                'belong' => ['recommend', 'dev'],
                'order' => ['recommend' => 6, 'dev' => 1],
                'visible' => true,
            ],
            [
                'name' => __('messages.tools.svg_viewer.title'),
                'description' => __('messages.tools.svg_viewer.description'),
                'route' => 'tools.svg-viewer',
                'icon' => 'M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z',
                'belong' => ['recommend', 'dev', 'marketing'],
                'order' => ['recommend' => 7, 'dev' => 1, 'marketing' => 5],
                'visible' => true,
            ],
            [
                'name' => __('messages.tools.qrcode.title'),
                'description' => __('messages.tools.qrcode.description'),
                'route' => 'tools.qrcode',
                'icon' => 'M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z',
                'belong' => ['recommend', 'marketing'],
                'order' => ['recommend' => 7, 'marketing' => 2],
                'visible' => true,
            ],
            [
                'name' => __('messages.tools.emoji_translator.title'),
                'description' => __('messages.tools.emoji_translator.description'),
                'route' => 'emoji-translator',
                'icon' => 'M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
                'belong' => ['recommend', 'ai', 'creative'],
                'order' => ['recommend' => 8, 'ai' => 7, 'creative' => 3],
                'visible' => true,
            ],
            [
                'name' => __('messages.tools.alchemy_of_soul.title'),
                'description' => __('messages.tools.alchemy_of_soul.description'),
                'route' => 'alchemy-of-soul',
                'icon' => 'M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
                'belong' => ['recommend', 'ai', 'creative'],
                'order' => ['recommend' => 9, 'ai' => 8, 'creative' => 4],
                'visible' => true,
            ],
            [
                'name' => __('messages.tools.ai_divination.title'),
                'description' => __('messages.tools.ai_divination.description'),
                'route' => 'ai-divination',
                'icon' => 'M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7',
                'belong' => ['recommend', 'ai', 'creative'],
                'order' => ['recommend' => 10, 'ai' => 2, 'creative' => 5],
                'visible' => true,
            ],
            [
                'name' => __('messages.tools.ai_english.title'),
                'description' => __('messages.tools.ai_english.description'),
                'featured' => app()->getLocale() != 'en' ? 1 : null,
                'url' => config('domains.english'),
                'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253',
                'belong' => ['ai'],
                'order' => ['ai' => 13],
                'visible' => true,
            ],
            [
                'name' => __('messages.tools.encode_decode.title'),
                'description' => __('messages.tools.encode_decode.description'),
                'route' => 'tools.encode',
                'icon' => 'M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z',
                'belong' => ['dev'],
                'order' => ['dev' => 2],
                'visible' => true,
            ],
            [
                'name' => __('messages.tools.time.title'),
                'description' => __('messages.tools.time.description'),
                'route' => 'tools.time',
                'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
                'belong' => ['dev'],
                'order' => ['dev' => 3],
                'visible' => true,
            ],
            [
                'name' => __('messages.tools.guid_generator.title'),
                'description' => __('messages.tools.guid_generator.description'),
                'route' => 'tools.guid',
                'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z',
                'belong' => ['dev'],
                'order' => ['dev' => 4],
                'visible' => true,
            ],
            [
                'name' => __('messages.tools.txt_splitter.title'),
                'description' => __('messages.tools.txt_splitter.description'),
                'route' => 'tools.txt-splitter',
                'icon' => 'M4 6h16M4 10h16M4 14h16M4 18h16',
                'belong' => ['text'],
                'order' => ['text' => 1],
                'visible' => true,
            ],
            [
                'name' => __('messages.tools.txt_diff.title'),
                'description' => __('messages.tools.txt_diff.description'),
                'route' => 'tools.txt-diff',
                'icon' => 'M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3',
                'belong' => ['text'],
                'order' => ['text' => 2],
                'visible' => true,
            ],
            [
                'name' => __('messages.tools.image_converter.title'),
                'description' => __('messages.tools.image_converter.description'),
                'route' => 'tools.image-converter',
                'icon' => 'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z',
                'belong' => ['dev', 'marketing'],
                'order' => ['dev' => 5, 'marketing' => 5],
                'visible' => true,
            ]

            // ... 其他工具配置
        ];

        $filteredTools = collect($tools)->filter(function ($tool) use ($category) {
            return (isset($tool['visible']) && $tool['visible']) && ($category === 'all' || in_array($category, $tool['belong']));
        });

        if ($category !== 'all') {
            $filteredTools = $filteredTools->sortBy(function ($tool) use ($category) {
                return $tool['order'][$category] ?? PHP_INT_MAX;
            });
        }

        if ($filteredTools->isEmpty()) {
            abort(404);
        }

        $displayTools = $filteredTools->values()->all();

        if (count($displayTools) === 1) {
            $tool = $displayTools[0];
            if (isset($tool['route'])) {
                return redirect()->route($tool['route']);
            } elseif (isset($tool['url'])) {
                return redirect($tool['url']);
            }
        }

        return view('tools.index', compact('categories', 'displayTools', 'category'));
    }


}
