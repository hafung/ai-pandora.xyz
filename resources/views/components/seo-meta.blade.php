@props([
'title' => '多功能在线工具聚合站 - 提升工作与生活效率 - 万能工具箱 - AI Pandora, 潘多拉之盒',
'description' => '探索我们的多功能在线工具，涵盖时间工具、AI智能翻译、文本处理、编码解码等，助您提升工作效率与生活品质。',
'keywords' => '在线工具, 时间工具, AI翻译, 文本处理, JSON转换, QR码生成, 编码解码, 专注力训练, 姓名评分, Emoji翻译, 占卜工具',
'fullTitle' => null
])
<title>@if ($fullTitle) {{ $fullTitle }} @else @if ($title && $title !== '万能工具箱 - AI Pandora, 潘多拉之盒') {{ $title }}
    -@endif 万能工具箱 - AI Pandora, 潘多拉之盒 @endif</title>
<meta name="description" content="{{ $description }}">
<meta name="keywords" content="{{ $keywords }}">
<meta name="author" content="AI-pandora">
<meta name="robots" content="index, follow">
<link rel="canonical" href="https://ai-pandora.xyz">
<meta property="og:title" content="{{ $title }}" />
<meta property="og:description" content="{{ $description }}" />
<meta property="og:image" content="https://ai-pandora.xyz/images/ai-pandora-logo.png" />
<meta property="og:type" content="website"/>
{{--<meta property="og:url" content="https://ai-pandora.xyz" />--}}
<meta property="og:url" content="{{ url()->current() }}" />
<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-2242417023054164"
        crossorigin="anonymous"></script>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "WebSite",
  "name": "AI-pandora",
  "url": "https://ai-pandora.xyz",
  "description": "{{ $description }}",
  "keywords": "{{ $keywords }}",
  "potentialAction": [
      {
        "@type": "ViewAction",
        "target": "https://ai-pandora.xyz/book-speed-read"
      },
      {
        "@type": "ViewAction",
        "target": "https://ai-pandora.xyz/category/ai-divination"
      },
      {
        "@type": "ViewAction",
        "target": "https://ai-pandora.xyz/category/name-scoring"
      },
      {
        "@type": "ViewAction",
        "target": "https://ai-pandora.xyz/category/ai-poetry"
      },
      {
        "@type": "ViewAction",
        "target": "https://ai-pandora.xyz/category/ai-name-generator"
      },
      {
        "@type": "ViewAction",
        "target": "https://ai-pandora.xyz/category/svg-viewer"
      },
      {
        "@type": "ViewAction",
        "target": "https://ai-pandora.xyz/category/json-convert"
      }
    ],
  "offers": {
    "@type": "Offer",
    "price": "0",
    "priceCurrency": "USD"
  }
}
</script>
