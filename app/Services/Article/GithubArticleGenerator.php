<?php

namespace App\Services\Article;


use App\Services\AI\AiConst;
use App\Services\AI\AiService;
use App\Services\AI\Params\AiChatParams;
use App\Services\Article\Contracts\ArticleGeneratorInterface;
use App\Services\Article\DTOs\ArticleMetadata;
use App\Services\Article\DTOs\GenerationResult;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class GithubArticleGenerator extends BaseArticleGenerator {

    private string $readmeContent;

    private $repoLink;

    /**
     * 设置 README 内容并进行预处理
     */
    public function setMainContent(string $content): self {
        $this->readmeContent = $content;
        $this->metadata = $this->extractMetadata($content);
        return $this;
    }

    public function setResponseFormat($format = 'markdown'): self {
        $this->responseFormat = $format;

        return $this;
    }

    public function setRepoLink($url = ''): static {
        $this->repoLink = $url;
        return $this;
    }


    /**
     * 提取项目元数据
     */
    private function extractMetadata(string $content): ArticleMetadata {
        $metadata = [
            'title' => '',
            'description' => '',
            'keywords' => [],
            'main_features' => [],
        ];

        // 提取第一个标题作为项目名
        if (preg_match('/^#\s*(.+)$/m', $content, $matches)) {
            $metadata['title'] = trim($matches[1]);
        }

        // 提取描述（通常在标题后的第一段）
        if (preg_match('/\n\n(.+?)\n\n/s', $content, $matches)) {
            $metadata['description'] = trim($matches[1]);
        }
        return new ArticleMetadata(
            title: $metadata['title'],
            description: $metadata['description'],
            keywords: $metadata['keywords'],
            mainFeatures: $metadata['main_features']
        );
    }

    /**
     * 生成文章提示词
     */
    protected function generatePrompt(): string {
        $prompt = <<<EOT
作为一名专注于向大众介绍开源项目的前沿科技博主，请根据给定的 GitHub 仓库 README 文件内容，撰写一篇**适合微信公众号发布的、引人入胜的中文介绍博文**。


**要求：**

- 创作一个信息量足且吸引眼球的标题，适用于微信文章传播，清晰点明项目主题。
- 用简洁易懂的语言解释项目的功能，避免过度技术术语。目标读者为具备基础技术概念，但不熟悉具体项目的受众。
- 突出项目的关键特性，并阐述其对用户的价值。侧重实用场景和价值主张，说明项目吸引读者的理由。
- 简要说明项目的目标用户，例如开发者、设计师、普通用户或特定领域人群。
- **请尽可能保留、并直接使用 README 内容中已有的图片链接，并保持其在 Markdown 中的引用格式。**
- 文章精炼，重点突出（理想字数 < 450 字）。
- 语言通俗易懂，尽可能避免技术术语，完全避免“AI味”，完全避免常用的AI用词。如需提及专业概念，请以易于理解的方式解释。
- 口语化表达，贴近微信公众号的阅读习惯。

**输入：**

- README 文件内容:
```
{$this->readmeContent}
```
- GitHub 链接:
```
{$this->repoLink}
```

**输出：**

**请直接输出结构合理的 Markdown 格式文本**
EOT;

        if ($this->responseFormat === 'markdown') {
            return $prompt;
        }
        if ($this->responseFormat === 'html') {
            return  <<<EOT
作为一名专注于向大众介绍开源项目的前沿科技博主，请根据给定的 GitHub 仓库 README 文件内容，撰写一篇**适合微信公众号发布的、引人入胜的中文博文**。

**要求：**

- 使用精致、优雅的css行内样式！
- 文章开头会开场白，自然过渡，同时引导用户关注该公众号。
- 保留原文中的技术术语，同时用简洁易懂的语言解释项目的功能。
- 口语化表达，用字、用词、段落衔接自然流畅。
- 禁止使用“首先”、“其次”、“然而”、“总的来说”、“总而言之”、“此外”、“综上所述”、“例如”等等这些副词。
- 突出项目的关键特性，并阐述其对用户的价值。告诉用户该项目的使用场景。
- 简要说明项目的目标用户，例如开发者、设计师、普通用户或特定领域人群。
- **不要引用 README 中的图片链接！不要使用emoji！**
- 文章精炼，重点突出（理想字数 < 400 字）。
- 不要引导用户去关注开源项目本身，而是引导用户去关注该公众号。（公众号二维码图片地址是： `http://mmbiz.qpic.cn/mmbiz_jpg/8VMXz6lKAa5Op1gt7QtbfVTTecOFrlhS2Fia4iarHGAhSrib7mFDrql90CqJ2hIQBXG0KY1qMoIarHkhicMibVx7SnQ/0?from=appmsg`）
- 结尾展示出这个项目的github地址（下面提供，不用超链接！）

**输入：**

- README 文件内容:
```
{$this->readmeContent}
```
- GitHub 链接:
```
{$this->repoLink}
```
**输出：**

**直接输出包含文章的HTML代码，不要包含任何Markdown标记（例如 ```html）；务必要使用css行内样式，与此同时，忽略常规html文件的头尾标签，直接从div开始返回**
EOT;
        }

        return $prompt;
    }

}
