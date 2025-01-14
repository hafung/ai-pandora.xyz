<?php

namespace App\Services\AI;

final class AiConst {


    const MODEL_CLAUDE35_SONNET_LATEST_VERSION = 'claude-3-5-sonnet-latest';
    const MODEL_CLAUDE35_SONNET_20241022_VERSION = 'claude-3-5-sonnet-20241022';
    const MODEL_CLAUDE35_SONNET_20240620_VERSION = 'claude-3-5-sonnet-20240620';

    const DOUBAO_LATEST_VERSION = 'Doubao-pro-32k';

    const KIMI_LATEST_VERSION = 'moonshot-v1-128k';

    const MODEL_GPT_4O = 'gpt-4o';
    const MODEL_GPT_4O_MINI = 'gpt-4o-mini';

    const MODEL_SWOOLE_DOUBAO = 'doubao';
    const MODEL_SWOOLE_KIMI = 'kimi';

    const MODEL_QWEN = 'qwen-long';
    const MODEL_DEEPSEEK_CHAT = 'deepseek-chat';

    const SWOOLE_MODELS_CHECKPOINTS = [
        self::MODEL_SWOOLE_DOUBAO => self::DOUBAO_LATEST_VERSION,
        self::MODEL_SWOOLE_KIMI => self::KIMI_LATEST_VERSION,
    ];

    const MODEL_OMG_TA_META_LLAMA_3_1_405B_INSTRUCT_TURBO = 'TA/meta-llama/Meta-Llama-3.1-405B-Instruct-Turbo';

    const MODEL_TA_QWEN2_5_72B_INSTRUCT_TURBO = 'TA/Qwen/Qwen2.5-72B-Instruct-Turbo';

    const MODEL_YI_LIGHTNING = 'yi-lightning';
    CONST MODEL_COMMAND_R_PLUS = 'command-r-plus';

    const MODEL_GLM_4_FLASH = 'glm-4-flash';

    const MODEL_GEMINI_1_5_PRO = 'gemini-1.5-pro';
    const MODEL_GEMINI_1_5_PRO_LATEST = 'gemini-1.5-pro-latest';
    const MODEL_GEMINI_1_5_FLASH_LATEST = 'gemini-1.5-flash-latest';

    const MODEL_META_LLAMA_3_1_405B_INSTRUCT_TURBO = 'meta-llama/Meta-Llama-3.1-405B-Instruct-Turbo';
    const MODEL_NVIDIA_LLAMA_3_1_NEMOTRON_70B_INSTRUCT_HF = 'nvidia/Llama-3.1-Nemotron-70B-Instruct-HF';
    const MODEL_QWEN2_5_72B_INSTRUCT_TURBO_LATEST = 'Qwen/Qwen2.5-72B-Instruct-Turbo';
    const MODEL_ZERO_ONE_AI_YI_34B_CHAT = 'zero-one-ai/Yi-34B-Chat';
    const MODEL_DEEPSEEK_CODE_33B_INSTRUCT = 'deepseek-ai/deepseek-coder-33b-instruct';
    const MODEL_META_LLAMA_3_2_90B_VISION_INSTRUCT_TURBO = 'meta-llama/Llama-3.2-90B-Vision-Instruct-Turbo';
    const MODEL_META_LLAMA_VISION_FREE = 'meta-llama/Llama-Vision-Free'; // free

    // silicon
    const MODEL_QWEN25_CODER_32B_INSTRUCT = 'Qwen/Qwen2.5-Coder-32B-Instruct';
    const MODEL_DEEPSEEK_V2_5 = 'deepseek-ai/DeepSeek-V2.5';
    const MODEL_QWEN25_72B_INSTRUCT_128K = 'Qwen/Qwen2.5-72B-Instruct-128K';
    const MODEL_QWEN25_CODER_7B_INSTRUCT = 'Qwen/Qwen2.5-Coder-7B-Instruct'; // 免费
    const MODEL_QWEN25_7B_INSTRUCT = 'Qwen/Qwen2.5-7B-Instruct'; // 免费
    const MODEL_QWEN2_7B_INSTRUCT = 'Qwen/Qwen2-7B-Instruct'; // 免费
    const MODEL_GLM_4_9B_CHAT = 'THUDM/glm-4-9b-chat'; // 免费
    const MODEL_FLUX_1_SCHNEL = 'black-forest-labs/FLUX.1-schnell';
    const MODEL_FUNAUDIOLM_SENSEVOICE_SMALL = 'FunAudioLLM/SenseVoiceSmall';
    const MODEL_STABILITYAI_STABLE_DIFFUSION_3_5_LARGE = 'stabilityai/stable-diffusion-3-5-large'; // 限时免费
    const MODEL_FLUX_1_DEV = 'black-forest-labs/FLUX.1-dev';
    const MODEL_FISHAUDIO_FISH_SPEECH_1_4 = 'fishaudio/fish-speech-1.4';
    const MODEL_RVC_BOSS_GPT_SOVITS = 'RVC-Boss/GPT-SoVITS';

    const MODEL_QWEN_PLUS_LATEST = 'qwen-plus-latest';
    const MODEL_QWEN_TURBO_LATEST = 'qwen-turbo-latest';
    const MODEL_QWEN_MAX_LATEST = 'qwen-max-latest';
    const MODEL_QWEN_PLUS_1127 = 'qwen-plus-1127';
    const MODEL_QWEN_PLUS_1125 = 'qwen-plus-1125';
    const MODEL_QWEN_TURBO_1101 = 'qwen-turbo-1101';
    const MODEL_QWQ_32B_PREVIEW = 'qwq-32b-preview';
    const MODEL_QWEN_CODER_TURBO = 'qwen-coder-turbo';
    const MODEL_QWEN_CODER_TURBO_LATEST = 'qwen-coder-turbo-latest';
    const MODEL_QWEN25_72B_INSTRUCT = 'qwen2.5-72b-instruct';

    const MODEL_LIST = [
        [
            'val' => self::MODEL_GPT_4O_MINI,
            'label' => 'GPT-4o-mini',
            'max_tokens' => 4096,
            'multiplier' => 0.075,
            'providers' => ['omg'],
        ],
        [
            'val' => self::MODEL_GPT_4O,
            'label' => 'GPT-4o',
            'max_tokens' => 8192,
            'multiplier' => 1.25,
            'providers' => ['omg'],
        ],
        [
            'val' => self::MODEL_GEMINI_1_5_FLASH_LATEST,
            'label' => 'Gemini 1.5 Flash',
            'max_tokens' => 4096,
            'multiplier' => 0.05,
            'providers' => ['omg'],
        ],
        [
            'val' => self::MODEL_GEMINI_1_5_PRO_LATEST,
            'label' => 'Gemini 1.5 Pro Latest',
            'max_tokens' => 4096,
            'multiplier' => 0.05,
            'providers' => ['omg'],
        ],
        [
            'val' => self::MODEL_DEEPSEEK_CHAT,
            'label' => 'DeepSeek Chat',
            'max_tokens' => 4096,
            'multiplier' => 0.05,
            'providers' => ['omg'],
        ],
        [
            'val' => self::MODEL_COMMAND_R_PLUS,
            'label' => 'Command R Plus',
            'max_tokens' => 4096,
            'multiplier' => 0.05,
            'providers' => ['omg'],
        ],
        [
            'val' => self::MODEL_YI_LIGHTNING,
            'label' => 'Yi Lightning',
            'max_tokens' => 4096,
            'multiplier' => 0.05,
            'providers' => ['omg'],
        ],
        [
            'val' => self::MODEL_TA_QWEN2_5_72B_INSTRUCT_TURBO,
            'label' => 'Qwen2.5-72B-Instruct-Turbo',
            'max_tokens' => 4096,
            'multiplier' => 0.05,
            'providers' => ['omg'],
        ],
        [
            'val' => self::MODEL_OMG_TA_META_LLAMA_3_1_405B_INSTRUCT_TURBO,
            'label' => 'Meta Llama 3.1 405B Instruct Turbo',
            'max_tokens' => 4096,
            'multiplier' => 0.05,
            'providers' => ['omg'],
        ],
        [
            'val' => self::MODEL_QWEN,
            'label' => 'Qwen Long',
            'max_tokens' => 4096,
            'multiplier' => 0.05,
            'providers' => ['swoole'],
        ],
        [
            'val' => self::MODEL_META_LLAMA_3_1_405B_INSTRUCT_TURBO,
            'label' => 'Meta Llama 3.1 405B Instruct Turbo',
            'max_tokens' => 4096,
            'multiplier' => 0.05,
            'providers' => ['together'],
        ],
        [
            'val' => self::MODEL_NVIDIA_LLAMA_3_1_NEMOTRON_70B_INSTRUCT_HF,
            'label' => 'Nvidia Llama 3.1 Nemotron 70B Instruct HF',
            'max_tokens' => 4096,
            'multiplier' => 0.05,
            'providers' => ['together'],
        ],
        [
            'val' => self::MODEL_QWEN2_5_72B_INSTRUCT_TURBO_LATEST,
            'label' => 'Qwen2.5-72B-Instruct-Turbo',
            'max_tokens' => 4096,
            'multiplier' => 0.05,
            'providers' => ['together'],
        ],
        [
            'val' => self::MODEL_ZERO_ONE_AI_YI_34B_CHAT,
            'label' => 'Yi 34B Chat',
            'max_tokens' => 4096,
            'multiplier' => 0.05,
            'providers' => ['together'],
        ],
        [
            'val' => self::MODEL_DEEPSEEK_CODE_33B_INSTRUCT,
            'label' => 'DeepSeek Code 33B Instruct',
            'max_tokens' => 4096,
            'multiplier' => 0.05,
            'providers' => ['together'],
        ],
        [
            'val' => self::MODEL_META_LLAMA_3_2_90B_VISION_INSTRUCT_TURBO,
            'label' => 'Meta Llama 3.2 90B Vision Instruct Turbo',
            'max_tokens' => 4096,
            'multiplier' => 0.05,
            'providers' => ['together'],
        ],
        [
            'val' => self::MODEL_META_LLAMA_VISION_FREE,
            'label' => 'Meta Llama Vision Free',
            'max_tokens' => 4096,
            'multiplier' => 0.05,
            'providers' => ['together'],
        ],
        [
            'val' => self::MODEL_QWEN25_CODER_32B_INSTRUCT,
            'label' => 'Qwen2.5 Coder 32B Instruct',
            'max_tokens' => 4096,
            'multiplier' => 0.05,
            'providers' => ['silicon'],
        ],
        [
            'val' => self::MODEL_DEEPSEEK_V2_5,
            'label' => 'DeepSeek V2.5',
            'max_tokens' => 4096,
            'multiplier' => 0.05,
            'providers' => ['silicon'],
        ],
        [
            'val' => self::MODEL_QWEN25_72B_INSTRUCT_128K,
            'label' => 'Qwen2.5-72B-Instruct-128K',
            'max_tokens' => 128,
            'multiplier' => 0.05,
            'providers' => ['silicon'],
        ],
        [
            'val' => self::MODEL_QWEN25_7B_INSTRUCT,
            'label' => 'Qwen2.5-7B-Instruct',
            'max_tokens' => 4096,
            'multiplier' => 0.05,
            'providers' => ['silicon'],
        ],
        [
            'val' => self::MODEL_GLM_4_9B_CHAT,
            'label' => 'GLM 4.9B Chat',
            'max_tokens' => 4096,
            'multiplier' => 0.05,
            'providers' => ['silicon'],
        ],


    ];



}
