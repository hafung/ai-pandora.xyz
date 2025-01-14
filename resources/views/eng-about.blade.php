@extends('layouts.eng-static')

@section('title', '关于我们')

@section('content')


    <h1 class="text-4xl font-bold mb-8 text-center text-indigo-700">关于本站</h1>

    <section class="mb-10 bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-2xl font-semibold mb-4 text-indigo-600">优势</h2>
        <ul class="list-disc list-inside text-gray-700 space-y-2">
            <li>免费 AND 免登录</li>
            <li>人工智能驱动的英语学习平台</li>
            <li>基于卡片的扩展记忆技术</li>
            <li>英英解释方式，助力深度理解</li>
            <li>专为具备基础英语能力的学习者量身定制</li>
        </ul>
    </section>

    <section class="mb-10 bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-2xl font-semibold mb-4 text-indigo-600">人工智能驱动</h2>
        <p class="text-gray-700 leading-relaxed">
            一切皆AI，从网站布局和样式到核心功能，甚至包括我们的Logo，都是使用先进的人工智能技术生成的。
        </p>
    </section>

    <section class="mb-10 bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-2xl font-semibold mb-4 text-indigo-600">开启您的英语学习之旅</h2>
        <p class="text-gray-700 leading-relaxed">
            无论您是想扩大词汇量，提高理解能力，还是仅仅想在英语技能方面更有自信，Learn English With AI 都将在您学习的每一步给予支持。今天就开始您的人工智能驱动的英语学习冒险吧！
        </p>
    </section>

<section class="bg-white p-6 rounded-lg shadow-md">
    <h2 class="text-2xl font-semibold text-indigo-600 mb-3">电子邮件 / Email</h2>
    <p>
        任何建议或者Bug反馈，请发送邮件至：<br>
        For general inquiries, feedback, or suggestions, please send an email to:<br>
        <a href="mailto:contact@ai-pandora.xyz" class="text-blue-600 hover:underline">contact@ai-pandora.xyz</a>
    </p>
</section>

@endsection
