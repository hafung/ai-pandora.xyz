<?php


use App\Services\AI\AiConst;

return [

    'llm_provider' => env('LLM_PROVIDER', 'swoole'),
    'default_llm_provider' => env('DEFAULT_LLM_PROVIDER', 'swoole'),

    'openai_api_key' => env('OPENAI_API_KEY'),
    'openai_api_host' => env('OPENAI_API_HOST'),

    /* alibaba */
    'ali_api_key' => env('ALI_API_KEY'),


    /* omg */
    'omg_api_host' => env('OMG_API_HOST', 'https://apic.ohmygpt.com'),
    'omg_api_key' => env('OMG_API_KEY'),


    /* together */
    'together_api_key' => env('TOGETHER_API_KEY'),
    'together_api_host' => 'https:://api.together.xyz',


    /* silicon */
    'silicon_api_key' => env('SILICON_API_KEY'),
    'silicon_api_host' => 'https://api.siliconflow.cn',


    'ephone_api_key' => env('EPHONE_API_KEY'),

    'foxai_api_key' => env('FOXAI_API_KEY'),

    'ai_pandora_api_key' => env('AI_PANDORA_API_KEY', '3345678&^*%&'),  // api key for own fe

    'music_adapter' => env('AI_MUSIC_ADAPTER'),
    'image_adapter' => env('AI_IMG_ADAPTER'),
    'image_adapter_async' => env('AI_IMG_ADAPTER_ASYNC'),

];
