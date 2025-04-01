<?php

namespace App\Jobs;

use App\Services\AI\Adapters\OpenAiAdapter;
use App\Services\AI\AiConst;
use App\Services\AI\AiService;
use App\Services\AI\Factories\AdapterFactory;
use App\Services\AI\Params\AiChatParams;
use App\Services\Article\CommonArticleGenerator;
use App\Services\Article\GithubArticleGenerator;
use App\Services\GithubReadmeFetcher;
use EasyWeChat\Factory;
use EasyWeChat\Kernel\Messages\Article;
use EasyWeChat\OfficialAccount\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Client\RequestException;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class ProcessWeChatArticle implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data;
    private $url;

    /**
     * @var mixed|string
     */
    private mixed $content;

    public function failed(Throwable $exception)
    {
        // 处理失败逻辑，例如记录日志
        Log::error("ProcessWeChatArticle Job failed: " . $exception->getMessage());
    }

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
        if (isset($this->data['url'])) {
            $this->url = $data['url'];
        }
        $this->content = $data['content'] ?? '';
    }


    public function getRandomWeChatImageMediaId($offset = 0, $limit = 20)
    {
        $mediaId = 0;
        $list = app(Application::class)->material->list('image', $offset, $limit);
        if (isset($list['item']) && is_array($list['item']) && count($list['item']) > 0) {
            $randomItem = $list['item'][array_rand($list['item'])]; // Select a random item
            $mediaId = $randomItem['media_id']; // Get the media_id from the random item
        }
        return $mediaId;
    }

    public function uploadImageToWeChat($url)
    {

        $mediaId = 0;

        $response = Http::timeout(60)->get($url);

        if ($response->successful()) {
            // Extract file extension from URL
            $urlExtension = pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION);

            // Always use jpg as the target extension
            $fileName = 'temp_' . uniqid() . '.jpg';
            $path = storage_path('app/public/temp/' . $fileName);

            // Ensure directory exists
            if (!file_exists(dirname($path))) {
                mkdir(dirname($path), 0755, true);
            }

            // If the source is webp, convert it to jpg
            if (strtolower($urlExtension) === 'webp') {
                // Save original webp file temporarily
                $tempWebpPath = storage_path('app/public/temp/webp_' . uniqid() . '.webp');
                file_put_contents($tempWebpPath, $response->body());

                // Convert webp to jpg using GD library
                if (function_exists('imagecreatefromwebp')) {
                    $image = imagecreatefromwebp($tempWebpPath);
                    if ($image) {
                        imagejpeg($image, $path, 90); // Save as jpg with 90% quality
                        imagedestroy($image);
                        unlink($tempWebpPath); // Remove temporary webp file
                    } else {
                        // Fallback if conversion fails
                        Log::warning("Failed to convert webp to jpg for: $url");
                        file_put_contents($path, $response->body());
                    }
                } else {
                    // Fallback if webp functions not available
                    Log::warning("WebP conversion not supported by PHP installation");
                    file_put_contents($path, $response->body());
                }
            } else {
                // For non-webp files, just save directly
                file_put_contents($path, $response->body());
            }

            $result = app(Application::class)->material->uploadImage($path);

            if (isset($result['media_id'])) {
                $mediaId = $result['media_id'];
                unlink($path);  // Delete temporary file
            } else {
                Log::error('ai生成图片：' . $url);
                Log::error('Failed to upload image to WeChat: ' . json_encode($result));
            }
        }

        return $mediaId;
    }


    public function submitArticleToWeChat($mediaId, Application $app): void
    {
        $responseArr = $app->base->httpPostJson('https://api.weixin.qq.com/cgi-bin/freepublish/submit', ['media_id' => $mediaId]);
        if (!empty($responseArr['errcode']) && isset($responseArr['errmsg'])) {
            Log::error('createDraftArticle.freepublish' . $responseArr['errmsg']);
        }
    }

    protected function createTitleAndDigest($articleContent): string
    {
        return "从以下文章中提炼出一个极致吸引人的标题(即\"标题党\")和一个不超过10个字的摘要。不要在标题中出体现文章里的核心信息!

文章内容:
```{$articleContent}```

严格按照以下格式返回：

标题: <提取的标题>
摘要: <提取的摘要>";
    }

    public function generateImageAndUploadToWechat(AiService $aiService, string $content)
    {
        try {

            $prompt = $aiService->callAgentOnce("Please generate a English image prompt suitable for illustrating the following user-provided content(It could be an article, a title, an abstract, or just a random thought (might be a bit abstract)):

**{$content}**

The prompt will be used for an AI image generation model. Please ensure the prompt is relevant and appropriate for the provided content.

Do not include any other explanations or text.");

            $urls = AdapterFactory::create(OpenAiAdapter::class)->generateImages([
                'prompt' => $prompt,
                'model' => 'dall-e-3',
                'aspect_ratio' => 'ASPECT_16_9',
            ]);

            if (isset($urls[0])) {

                return $this->uploadImageToWeChat($urls[0]);
            }
        } catch (Throwable $e) {
            Log::error('封面图片生成失败：' . $e->getMessage());
            return 0;
        }
    }

    public function createGithubArticle()
    {

    }

    public function generateArticleByGivenContent(AiService $aiService, Application $app)
    {

        if (!$this->content) {
            return;
        }

        $generator = new CommonArticleGenerator($aiService);
        $result = $generator
            ->setResponseFormat('html')
            ->setMainContent($this->content)
            ->setArticleType($this->data['type'] ?? 'news')
            ->generateArticle();

        if ($result->success) {
            $article = $result->article;
        } else {
            Log::error('ai_generate: ' . $result->error);
            return;
        }

        if (!$article) {
            Log::error('ai_generate: ' . $result->error ?? 'unknown err: 文章生成失败');
            return;
        }

        $mediaId = 0;

        $justGetFromStock = mt_rand(0, 1) === 1;

        if ($justGetFromStock) {
            $mediaId = $this->getRandomWeChatImageMediaId();
        }

        if ($mediaId === 0) {
            $mediaId = $this->generateImageAndUploadToWechat($aiService, $this->content);
        }

        if (!$mediaId) {
            $mediaId = $this->getRandomWeChatImageMediaId();
        }

        $articleSummary = $aiService->callAgentOnce($this->createTitleAndDigest($this->content));

        preg_match('/标题:\s*(.+)\s*摘要:\s*(.+)/s', $articleSummary, $matches);

        if ($matches) {
            $title = trim($matches[1]);
            $digest = trim($matches[2]);
        } else {
            $title = "史上最详细的发cai攻略";
            $digest = "发CAI宝典💴💴💴";
        }

        $responseArr = $app->base->httpPostJson('https://api.weixin.qq.com/cgi-bin/draft/add', [
            'articles' => [
                [
                    "article_type" => "news",
                    "title" => $title,
                    "author" => 'TuringNexus',
                    "digest" => $digest,
                    'content' => $article,
                    'thumb_media_id' => $mediaId,
                    "need_open_comment" => 1,
                    "only_fans_can_comment" => 1
                ]
            ]
        ]);

        if (isset($responseArr['media_id']) && !empty($this->data['publishImmediately'])) {
            $responseArr = $app->base->httpPostJson('https://api.weixin.qq.com/cgi-bin/freepublish/submit', ['media_id' => $responseArr['media_id']]);
            if (!empty($responseArr['errcode']) && isset($responseArr['errmsg'])) {
                Log::error('createDraftArticle.freepublish' . $responseArr['errmsg']);
            }
        }

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(AiService $aiService, Application $app)
    {

        if ($this->content && isset($this->data['type']) && $this->data['type'] !== 'github') {
            $this->generateArticleByGivenContent($aiService, $app);
            return;
        }


        $readmeFetcher = GitHubReadmeFetcher::create($this->url);
        $readmeContent = $readmeFetcher->fetch();

        if (!$readmeContent) {
            return;
        }

        $model = AiConst::MODEL_GPT_4O;

        $targetProvider = $aiService->getProviderByModelName($model);

        if ($aiService->getAdapterProvider() !== $targetProvider) {
            $aiService->resetServiceAdapterByProvider($targetProvider);
        }

        $aiChatParams = new AiChatParams([
            'model' => $model,
            'user_prompt' => $this->createTitleAndDigest($readmeContent),
            'use_context' => false,
            'temperature' => 0.7,
            'checkpoint' => AiConst::SWOOLE_MODELS_CHECKPOINTS[$model] ?? ''
        ]);

        $articleSummary = $aiService->chat($aiChatParams);

        preg_match('/标题:\s*(.+)\s*摘要:\s*(.+)/s', $articleSummary, $matches);

        if ($matches) {
            $title = trim($matches[1]);
            $digest = trim($matches[2]);
        } else {
            $title = "史上最详细的发财攻略";
            $digest = "发财宝典💴💴💴";
        }

        $mediaId = 0;

        if (mt_rand(0, 1) === 1) {
            $mediaId = $this->getRandomWeChatImageMediaId(); // 从媒体库里随机选择一个 最大20
        }

        if ($mediaId === 0) {

            try {

                $prompt = $aiService->callAgentOnce("Please generate a English image prompt suitable for illustrating the following user-provided content(It could be an article, a title, an abstract, or just a random thought (might be a bit abstract)):

**{$readmeContent}**

The prompt will be used for an AI image generation model. Please ensure the prompt is relevant and appropriate for the provided content.

Do not include any other explanations or text.");

                $urls = AdapterFactory::create(OpenAiAdapter::class)->generateImages([
                    'prompt' => $prompt,
                    'model' => 'dall-e-3',
                    'aspect_ratio' => 'ASPECT_16_9',
                ]);

                if (isset($urls[0])) {

                    $mediaId = $this->uploadImageToWeChat($urls[0]);
                }
            } catch (Throwable $e) {
                Log::error('封面图片生成失败：' . $e->getMessage());
            }

        }

        if (!$mediaId) {
            $mediaId = $this->getRandomWeChatImageMediaId();
        }

        $articleGenerator = new GithubArticleGenerator($aiService);

        $result = $articleGenerator
            ->setResponseFormat('html')
            ->setMainContent($readmeContent)
            ->setRepoLink($this->url)
            ->generateArticle();

        if ($result->success) {
            $article = $result->article;
        } else {
            Log::error('ai_generate: ' . $result->error);
            return;
        }

        if (!$article) {
            Log::error('ai_generate: ' . $result->error ?? 'unknown err： $article 为空，生成文章失败');
            return;
        }

        $responseArr = $app->base->httpPostJson('https://api.weixin.qq.com/cgi-bin/draft/add', [
            'articles' => [
                [
                    "article_type" => "news",
                    "title" => $title,
                    "author" => 'TuringNexus',
                    "digest" => $digest,
                    'content' => $article,
                    'thumb_media_id' => $mediaId,
                    "need_open_comment" => 1,
                    "only_fans_can_comment" => 1
                ]
            ]
        ]);


//        Log::notice('wechat-article-response: save to draft - ' . json_encode($responseArr));

        if (isset($responseArr['media_id']) && !empty($this->data['publishImmediately'])) {
//            $responseArr = $app->base->httpPostJson('https://api.weixin.qq.com/cgi-bin/freepublish/submit', ['media_id' => $responseArr['media_id']]);
//            if (!empty($responseArr['errcode']) && isset($responseArr['errmsg'])) {
//                Log::error('createDraftArticle.freepublish' . $responseArr['errmsg']);
//            }
            $this->submitArticleToWeChat($responseArr['media_id'], $app);
        }

    }

}
