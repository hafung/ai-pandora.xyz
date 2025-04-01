<?php

namespace App\Services\Article;

use App\Services\Article\DTOs\ArticleMetadata;

class CommonArticleGenerator extends BaseArticleGenerator {

    private string $mainContent;
    private ?string $title = null;
    private array $keywords = [];
    private string $articleType = 'news'; // Default type

    private const ARTICLE_TYPES = [
        'news',              // 资讯文章
        'micro_fiction',     // 微小说
        'urban_romance',     // 都市情感故事
        'toxic_chicken_soup' // 毒鸡汤
    ];

    public function setArticleType(string $type): self {
        if (!in_array($type, self::ARTICLE_TYPES)) {
            throw new \InvalidArgumentException(
                "Invalid article type: {$type}. Available types: " . implode(', ', self::ARTICLE_TYPES)
            );
        }

        $this->articleType = $type;
        return $this;
    }

    public function setMainContent(string $content): self {
        $this->mainContent = $content;
        return $this;
    }

    public function setTitle(?string $title): self {
        $this->title = $title;
        return $this;
    }


    public function setKeywords(array $keywords): self {
        $this->keywords = $keywords;
        return $this;
    }


    public function initMetadata(): self {
        $description = substr($this->mainContent, 0, 100) . '...';
        $title = $this->title ?? $this->getDefaultTitleByType();

        $this->metadata = new ArticleMetadata(
            title: $title,
            description: $description,
            keywords: $this->keywords,
            mainFeatures: []
        );

        return $this;
    }

    private function getDefaultTitleByType(): string {
        return match($this->articleType) {
            'news' => '资讯文章',
            'micro_fiction' => '微小说',
            'urban_romance' => '都市情感故事',
            'toxic_chicken_soup' => '毒鸡汤',
            default => '资讯文章'
        };
    }


    protected function generatePrompt(): string {

        return str_replace('{CONTENT}', $this->mainContent, $this->responseFormat === 'html'
            ? $this->getHtmlPromptTemplate()
        : $this->getMarkdownPromptTemplate());
    }

    private function getMarkdownPromptTemplate(): string {
        return match($this->articleType) {
            'news' => $this->getNewsMarkdownPrompt(),
            'micro_fiction' => $this->getMicroFictionMarkdownPrompt(),
            'urban_romance' => $this->getUrbanRomanceMarkdownPrompt(),
            'toxic_chicken_soup' => $this->getToxicChickenSoupMarkdownPrompt(),
            default => $this->getNewsMarkdownPrompt()
        };
    }

    private function getHtmlPromptTemplate(): string {
        return match($this->articleType) {
            'news' => $this->getNewsHtmlPrompt(),
            'micro_fiction' => $this->getMicroFictionHtmlPrompt(),
            'urban_romance' => $this->getUrbanRomanceHtmlPrompt(),
            'toxic_chicken_soup' => $this->getToxicChickenSoupHtmlPrompt(),
            default => $this->getNewsHtmlPrompt()
        };
    }

    private function getNewsMarkdownPrompt(): string {
        return <<<EOT
作为一名专业的科技资讯编辑，请根据提供的内容撰写一篇适合微信公众号发布的、引人入胜的中文资讯文章。

**要求：**

- 创作一个信息量足且吸引眼球的标题（如果未提供标题）
- 文章需要客观、准确地呈现原始内容的主要信息点
- 使用简洁易懂的语言，避免过度技术术语
- 文章结构清晰，重点突出（理想字数 < 450 字）
- 语言通俗易懂，完全避免"AI味"，完全避免常用的AI用词
- 口语化表达，贴近微信公众号的阅读习惯
- 文章末尾引导用户去关注该公众号。（公众号二维码图片地址是： `http://mmbiz.qpic.cn/mmbiz_jpg/8VMXz6lKAa5Op1gt7QtbfVTTecOFrlhS2Fia4iarHGAhSrib7mFDrql90CqJ2hIQBXG0KY1qMoIarHkhicMibVx7SnQ/0?from=appmsg`）

**输入：**

核心资讯概要:
{CONTENT}

**输出：**

**请直接输出结构合理的 Markdown 格式文本**
EOT;
    }

    private function getNewsHtmlPrompt(): string {
        return <<<EOT
你是一位资深科技专栏评论员，请基于以下信息，撰写一篇面向大众读者的科技资讯博文：

【信息】
```{CONTENT}```

【要求】
1.  以引人入胜的引言开篇，开篇可以分享你与该内容相关的个人体会、个人经历、个人见闻；无需标题。
2.  文风简洁、自然、口语化，像和朋友聊天一样，对信息进行有洞察力的分析和适当扩写；多角度分析其产生的影响。
3.  表达你对这项技术的看法（兴奋/担忧/期待等）。
4.  避免使用“首先”、“其次”、“然而”等连接词。
5.  多使用独特的、新颖的、个性化的词语、句式（长短句结合）。
6.  结尾处进行哲理性的升华，并加入犀利“金句”；可以适当自嘲、玩梗。
7.  字数控制在350字以内。
8.  文章末尾引导用户关注公众号（二维码链接：`http://mmbiz.qpic.cn/mmbiz_jpg/8VMXz6lKAa5Op1gt7QtbfVTTecOFrlhS2Fia4iarHGAhSrib7mFDrql90CqJ2hIQBXG0KY1qMoIarHkhicMibVx7SnQ/0?from=appmsg`）。
9.  禁止使用过于专业的术语（如果必须使用，要进行简要解释）；避免使用陈词滥调。
10. 直接输出符合以下要求的 HTML 代码（不要有"html```"这种markdown格式），且不含`<meta>`等多余标签，直接从`<div>`开始：
    *   使用行内 CSS 样式。
    *   排版优雅，参考知名报刊杂志（如《纽约客》）的风格。
    *   字体、颜色、间距、段落、对齐方式等细节需精心设计。
        1.  引言部分字体和正文部分字体一样大，并且给引言部分设置左边框（边框颜色、粗细可以不用完全一致）。
        2.  段落不首行缩进。
        3.  公众号二维码图片居中。

【严格按照以下HTML 模板及样式要求】

**HTML 模板：**

<div style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; line-height: 1.6; color: #333; max-width: 650px; margin: 0 auto; padding: 20px;">
    <p style="font-size: 16px; color: #555; margin-bottom: 25px; padding: 15px; border-left: 4px solid #409EFF; ">
      {引言部分}
    </p>
    <p style="font-size: 16px; margin-bottom: 15px;">
      {正文段落1}
    </p>
    <p style="font-size: 16px; margin-bottom: 15px;">
       {正文段落2}
    </p>

    <!-- 更多正文段落... -->

    <p style="font-size: 16px; margin-bottom: 15px; font-style: italic; color: #777;">
      {结尾升华及金句}
    </p>
    <div style="text-align: center; margin-top: 30px;">
      <p style="font-size: 14px; color: #888; margin-bottom:10px">
        欢迎关注我们的公众号，获取更多精彩内容！
      </p>
      <img src="http://mmbiz.qpic.cn/mmbiz_jpg/8VMXz6lKAa5Op1gt7QtbfVTTecOFrlhS2Fia4iarHGAhSrib7mFDrql90CqJ2hIQBXG0KY1qMoIarHkhicMibVx7SnQ/0?from=appmsg" alt="公众号二维码" style="width: 150px; height: 150px;">
    </div>
  </div>
EOT;
    }

    // Micro Fiction prompt templates
    private function getMicroFictionMarkdownPrompt(): string {
        return <<<EOT
作为一名专业的微小说创作者，请根据提供的内容创作一篇简短但引人入胜的微小说。

**要求：**

- 创作一个吸引读者眼球的标题（如果未提供标题）
- 故事精炼而有力，在300字左右完成故事的起承转合
- 故事需要有明确的主题和令人回味的结局
- 可以包含悬疑、反转或深刻的人生感悟
- 语言要精准、富有张力，每个字都有其存在的价值
- 确保文本具有文学性和可读性，完全避免"AI味"
- 文章末尾引导用户去关注该公众号。（公众号二维码图片地址是： `http://mmbiz.qpic.cn/mmbiz_jpg/8VMXz6lKAa5Op1gt7QtbfVTTecOFrlhS2Fia4iarHGAhSrib7mFDrql90CqJ2hIQBXG0KY1qMoIarHkhicMibVx7SnQ/0?from=appmsg`）

**输入：**

关键词/主题:
{CONTENT}

**输出：**

**请直接输出结构合理的 Markdown 格式文本**
EOT;
    }

    private function getMicroFictionHtmlPrompt(): string {
        return <<<EOT
你是文学巨匠，请所提供的要点/关键词/主题：```{CONTENT}```，创作一篇**微小说**。

要求：
* 在300字左右完成一个小故事，包含起承转合
* 故事需有深意，带给读者思考
* 结尾需有反转或令人回味的结局
* 语言精炼而有力，富有文学性
* 文章末尾引导用户去关注该公众号。（公众号二维码图片地址是： `http://mmbiz.qpic.cn/mmbiz_jpg/8VMXz6lKAa5Op1gt7QtbfVTTecOFrlhS2Fia4iarHGAhSrib7mFDrql90CqJ2hIQBXG0KY1qMoIarHkhicMibVx7SnQ/0?from=appmsg`）
* **直接输出 HTML 代码（不要用markdown格式包裹，比如"```"）**，务必使用 **行内样式**，样式优雅、深邃；不要包含 `<meta>` 等多余标签，直接从div标签开始。
EOT;
    }

    // Urban Romance prompt templates
    private function getUrbanRomanceMarkdownPrompt(): string {
        return <<<EOT
作为一名都市情感故事专业作家，请根据提供的内容创作一篇现代都市情感故事。

**要求：**

- 创作一个引人入胜的都市情感故事标题（如果未提供标题）
- 故事背景设定在现代都市环境中
- 情节要具有真实感，贴近现代年轻人的生活和情感经历
- 角色刻画要立体，情感描写要细腻
- 故事可以包含爱情、友情、亲情等元素，但要有明确的情感主线
- 语言要流畅自然，避免说教，用细节和对话推动情节发展
- 总字数控制在1000字左右，要确保故事的完整性
- 文章末尾引导用户去关注该公众号。（公众号二维码图片地址是： `http://mmbiz.qpic.cn/mmbiz_jpg/8VMXz6lKAa5Op1gt7QtbfVTTecOFrlhS2Fia4iarHGAhSrib7mFDrql90CqJ2hIQBXG0KY1qMoIarHkhicMibVx7SnQ/0?from=appmsg`）

**输入：**

故事关键字/主题/背景:
{CONTENT}

**输出：**

**请直接输出结构合理的 Markdown 格式文本**
EOT;
    }

    // 如果是情感故事，可以让AI模仿一位女性在跟闺蜜分享八卦的口吻，这样文章更具有叙事性，也会让读者感觉文章里所说的事情就发生在他周围。
    private function getUrbanRomanceHtmlPrompt(): string {
        return <<<EOT
请根据以下核心信息点（可能是故事关键字||主题||背景）：```{CONTENT}```，模仿一位女性在跟闺蜜分享八卦的口吻，创作一篇**现代都市情感故事**。

要求：
* 故事设定在现代都市环境中
* 情节要真实，贴近当代年轻人的生活体验
* 角色形象要立体，有成长或改变
* 情感描写要细腻，通过细节和对话展现人物心理
* 总字数控制在500字以内
* 文章末尾引导用户去关注该公众号。（公众号二维码图片地址是： `http://mmbiz.qpic.cn/mmbiz_jpg/8VMXz6lKAa5Op1gt7QtbfVTTecOFrlhS2Fia4iarHGAhSrib7mFDrql90CqJ2hIQBXG0KY1qMoIarHkhicMibVx7SnQ/0?from=appmsg`）
* **直接输出 HTML 代码（不要用markdown格式包裹，比如"```"）**，务必使用 **行内样式**，样式优雅、简洁；不要包含 `<meta>` 等多余标签，直接从div标签开始。
* 具体样式布局要求：
    *   字体：使用 "Helvetica Neue", Helvetica, Arial, sans-serif;
    *   颜色：正文 #333，标题 #222
    *   行高：正文 1.6，标题 1.2
    *   段落间距：1.5em
    *   标题居中
    *   使用白色背景
    *   尽量减少颜色使用
    *   较大的留白
EOT;
    }

    // Toxic Chicken Soup prompt templates
    private function getToxicChickenSoupMarkdownPrompt(): string {
        return <<<EOT
作为一名毒鸡汤文案创作者，请根据提供的内容创作一篇带有反讽意味的毒鸡汤文。

**要求：**

- 文案表面看起来是激励人心的，但实际上带有社会反讽或黑色幽默
- 语言要简洁有力，富有节奏感和记忆点
- 可以运用夸张、对比、反转等修辞手法增强效果
- 内容要巧妙地指向现实社会问题或生活困境
- 避免直白的负能量或攻击性语言，用智慧和幽默传达讽刺
- 总字数控制在300字以内，追求一针见血的表达

**输入：**
{TITLE}
{KEYWORDS}

- 内容/主题:
{CONTENT}


**输出：**

**请直接输出结构合理的 Markdown 格式文本**
EOT;
    }

    private function getToxicChickenSoupHtmlPrompt(): string {
        return <<<EOT
根据我提供的1-2句话，创作一篇讽刺性"毒鸡汤"文章。要求：
1. 保持原句语言（e.g 中文输入生成中文文章）
2. 文笔优雅、流畅、犀利，充满反讽和尖锐观点
3. 在看似励志的表面下藏有现实讽刺
4. 结尾应当有力量，留下深刻思考
5. 字数控制在400字以内，首行不要缩进
6. 文章末尾引导用户去关注该公众号（居中显示！公众号二维码图片地址是： `http://mmbiz.qpic.cn/mmbiz_jpg/8VMXz6lKAa5Op1gt7QtbfVTTecOFrlhS2Fia4iarHGAhSrib7mFDrql90CqJ2hIQBXG0KY1qMoIarHkhicMibVx7SnQ/0?from=appmsg`）
7. **直接输出 HTML 代码（不要用markdown格式包裹，比如"```"）**，务必使用 **行内样式**，整体排版样式优雅、高级；不要包含 `<meta>` 等多余标签，直接从div标签开始。

我的输入句子是：```{CONTENT}```

**直接返回包含文章的HTML原始代码，不要作任何多余解释**

**严格按照以下HTML模板返回文章：**
<div style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 16px; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #eee; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);">
    <p style="margin-bottom: 15px;">{文章段落1}</p>
    <p style="margin-bottom: 15px;">{文章段落2}</p>
    <!-- 可以根据需要添加更多段落 -->
    <p style="margin-bottom: 15px;font-style: italic; text-align:right;">{文章结尾段落}</p>
    <div style="text-align: center; margin-top: 30px;">
        <p style="font-size: 14px; color: #666; margin-bottom: 10px;">更多精彩，欢迎关注我们的公众号</p>
        <img src="http://mmbiz.qpic.cn/mmbiz_jpg/8VMXz6lKAa5Op1gt7QtbfVTTecOFrlhS2Fia4iarHGAhSrib7mFDrql90CqJ2hIQBXG0KY1qMoIarHkhicMibVx7SnQ/0?from=appmsg" alt="公众号二维码" style="width: 150px; height: 150px;">
    </div>
</div>
EOT;
    }


}
