<?php


use App\Services\AI\AiConst;

return [

    'llm_provider' => env('LLM_PROVIDER', 'swoole'),
    'default_llm_provider' => env('DEFAULT_LLM_PROVIDER', 'swoole'),

    'openai_api_key' => env('OPENAI_API_KEY'),
    'openai_api_host' => env('OPENAI_API_HOST'),

    /* alibaba */
    'ali_api_key' => env('ALI_API_KEY'),
    'ali_llm_for_chat' => AiConst::MODEL_QWEN_PLUS_1127,
    'ali_llm_for_cheapest' => AiConst::MODEL_QWEN_TURBO_1101,
    'ali_llm_for_writing' => AiConst::MODEL_QWEN_PLUS_LATEST,
    'ali_llm_for_coding' => AiConst::MODEL_QWEN_CODER_TURBO_LATEST,


    /* omg */
    'omg_api_host' => env('OMG_API_HOST', 'https://apic.ohmygpt.com'),
    'omg_api_key' => env('OMG_API_KEY'),
    'omg_llm_for_cheapest' => AiConst::MODEL_COMMAND_R_PLUS,
    'omg_llm_for_writing' => AiConst::MODEL_YI_LIGHTNING,
    'omg_llm_for_coding' => AiConst::MODEL_TA_QWEN2_5_72B_INSTRUCT_TURBO,
    'omg_llm_for_chat' => AiConst::MODEL_OMG_TA_META_LLAMA_3_1_405B_INSTRUCT_TURBO,


    /* together */
    'together_api_key' => env('TOGETHER_API_KEY'),
    'together_api_host' => 'https:://api.together.xyz',
    'together_llm_for_cheapest' => AiConst::MODEL_META_LLAMA_VISION_FREE,
    'together_llm_for_writing' => AiConst::MODEL_QWEN2_5_72B_INSTRUCT_TURBO_LATEST,
    'togeher_llm_for_coding' => AiConst::MODEL_QWEN2_5_72B_INSTRUCT_TURBO_LATEST,
    'togeher_llm_for_chat' => AiConst::MODEL_META_LLAMA_3_1_405B_INSTRUCT_TURBO,

    /* silicon */
    'silicon_api_key' => env('SILICON_API_KEY'),
    'silicon_api_host' => 'https://api.siliconflow.cn',
    'silicon_llm_for_cheapest' => AiConst::MODEL_QWEN25_7B_INSTRUCT,
    'silicon_llm_for_writing' => AiConst::MODEL_DEEPSEEK_V2_5,
    'silicon_llm_for_coding' => AiConst::MODEL_QWEN25_CODER_32B_INSTRUCT,
    'silicon_llm_for_chat' => AiConst::MODEL_QWEN25_72B_INSTRUCT_128K,

    'ephone_api_key' => env('EPHONE_API_KEY'),

    'foxai_api_key' => env('FOXAI_API_KEY'),

    'ai_pandora_api_key' => env('AI_PANDORA_API_KEY', '3345678&^*%&'),  // api key for own fe

    'music_adapter' => env('AI_MUSIC_ADAPTER'),
    'image_adapter' => env('AI_IMG_ADAPTER'),
    'image_adapter_async' => env('AI_IMG_ADAPTER_ASYNC'),

];
