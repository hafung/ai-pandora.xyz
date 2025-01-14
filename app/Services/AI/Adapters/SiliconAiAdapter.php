<?php

namespace App\Services\AI\Adapters;

use App\Services\AI\AiConst;
use App\Traits\ImageProcessTrait;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\RequestException;

class SiliconAiAdapter extends OpenAiAdapter {

    use ImageProcessTrait;

    const IMAGE_GENERATE_ENDPOINT = '/v1/images/generations';

    public function __construct() {
        parent::__construct();
        $this->apiHost = config("ai.silicon_api_host");
        $this->apiKey = config("ai.silicon_api_key");
    }

    /**
     * Generate images using the SiliconFlow API
     *
     * @param $opt array{
     *     model?: string,
     *     prompt: string,
     *     image_size?: string,
     *     negative_prompt?: string|null,
     *     batch_size?: int,
     *     seed?: int|null,
     *     num_inference_steps?: int|null,
     *     guidance_scale?: float|null,
     *     prompt_enhancement?: bool|null
     * }  An array of options for image generation:
     *        - model: The model to use (default: AiConst::MODEL_FLUX_1_SCHNEL)
     *        - prompt: The prompt for image generation
     *        - image_size: The size of the generated image (default: '1024x1024')
     *        - negative_prompt: The negative prompt (optional)
     *        - batch_size: The number of images to generate (default: 1)
     *        - seed: The seed for random number generation (optional)
     *        - num_inference_steps: The number of inference steps (optional)
     *        - guidance_scale: The guidance scale (optional)
     *        - prompt_enhancement: Whether to enhance the prompt (optional)
     *
     * @return array{
     *     images: array<int, array{url: string}>,
     *     timings: array{inference: int},
     *     seed: int
     * } The response from the API containing generated image information
     *
     * @throws RequestException When the API request fails
     */
    public function generateImages(array $opt): array {

        if (isset($opt['aspect_ratio'])) {
            $opt['image_size'] = $this->calculateSizeFromAspectRatio($opt['aspect_ratio'], 'silicon');
        }

        $requiredParams = [
            'model' => $opt['model'] ?? AiConst::MODEL_FLUX_1_SCHNEL,
            'prompt' => $opt['prompt'],
            'image_size' => $opt['image_size'] ?? $opt['size'] ?? '1024x1024',
        ];

        $optionalParams = [
            'negative_prompt' => $opt['negative_prompt'] ?? null,
            'batch_size' => $opt['batch_size'] ?? $opt['n'] ?? 1,
            'seed' => $opt['seed'] ?? null,
            'num_inference_steps' => $opt['num_inference_steps'] ?? null,
            'guidance_scale' => $opt['guidance_scale'] ?? null,
            'prompt_enhancement' => $opt['prompt_enhancement'] ?? null,
        ];

        // 移除所有值为 null 的可选参数
        $optionalParams = array_filter($optionalParams, function ($value) {
            return $value !== null;
        });

        // 合并必需参数和可选参数
        $requestParams = array_merge($requiredParams, $optionalParams);

        $response = Http::withToken($this->apiKey)
            ->post($this->apiHost . self::IMAGE_GENERATE_ENDPOINT, $requestParams);

        $response->throw();  // This will throw an exception for 4xx and 5xx errors

        $responseData = $response->json();
        if (isset($responseData['images'])) {
            return array_map(function ($image) {
                return $image['url'];
            }, $responseData['images']);
        }
        return [];
    }


}
