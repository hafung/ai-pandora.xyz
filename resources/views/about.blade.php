@extends('layouts.tools-static')

@section('title', __('about_page.title'))

@section('header', __('about_page.title'))

@section('content')
    <div class="prose max-w-none">
        <p class="text-lg mb-4">{{ __('about_page.welcome') }}</p>

        <h2 class="text-2xl font-semibold mt-6 mb-3">{{ __('about_page.our_mission') }}</h2>
        <p>{{ __('about_page.mission_content') }}</p>

        <h2 class="text-2xl font-semibold mt-6 mb-3">{{ __('about_page.our_services') }}</h2>
        <ul class="list-disc pl-5 space-y-2">
            <li><strong>{{ __('about_page.ai_tools') }}</strong></li>
            <li><strong>{{ __('about_page.practical_tools') }}</strong></li>
        </ul>

        <h2 class="text-2xl font-semibold mt-6 mb-3">{{ __('about_page.our_commitment') }}</h2>
        <ul class="list-disc pl-5 space-y-2">
            <li><strong>{{ __('about_page.free_use') }}</strong></li>
            <li><strong>{{ __('about_page.no_login') }}</strong></li>
            <li><strong>{{ __('about_page.privacy') }}</strong></li>
            <li><strong>{{ __('about_page.innovation') }}</strong></li>
        </ul>

        <p class="text-lg mb-4 mt-4">
            我们非常重视您的反馈和建议。虽然 AI-pandora 是一个免登录、不收集用户信息的平台，但我们仍然希望听到您的声音。以下是几种您可以联系我们的方式：<br><br>
            We highly value your feedback and suggestions. Although AI-pandora is a platform that doesn't require login or collect user information, we still want to hear from you. Here are a few ways you can contact us:
        </p>

        <h2 class="text-2xl font-semibold mt-6 mb-3">电子邮件 / Email</h2>
        <p>
            对于一般询问、反馈或建议，请发送电子邮件至：<br>
            For general inquiries, feedback, or suggestions, please send an email to:<br>
            <a href="mailto:contact@ai-pandora.xyz" class="text-blue-600 hover:underline">contact@ai-pandora.xyz</a>
        </p>

        <h2 class="text-2xl font-semibold mt-6 mb-3">Twitter / X</h2>
        <p>
            关注我们的 Twitter 账号以获取最新更新和公告：<br>
            Follow our Twitter account for the latest updates and announcements:<br>
            <a href="https://x.com/Ai_Pandora_xyz" target="_blank" rel="noopener noreferrer" class="text-blue-600 hover:underline">@Ai_Pandora_xyz</a>
        </p>

        <p class="mt-6">{{ __('about_page.thank_you') }}</p>
    </div>
@endsection
