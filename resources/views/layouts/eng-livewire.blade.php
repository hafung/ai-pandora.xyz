<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="AI驱动的英语学习网站，极简式卡片风格，完全免费 & 免登录。AI-powered English learning website with a minimalist card-style design, completely free and no login required.">
    <meta name="keywords" content="">
    <title>Learn English With AI</title>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&display=swap" rel="stylesheet">
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    @livewireStyles
    @stack('styles')
    <script src="{{ mix('js/app.js') }}" defer></script>
</head>
<body class="bg-gray-100 min-h-screen">

<x-eng-header />

<main>
    {{ $slot }}
</main>

<footer class="bg-gray-200 py-4 text-sm text-gray-600">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row justify-between items-center">
            <div class="mb-2 sm:mb-0">
                &copy; {{ date('Y') }} Learn English With AI. All rights reserved. | Version 1.0.0
            </div>
            <nav class="flex space-x-4">
                <a target="_blank" href="{{ route('about') }}">About</a>
                <a target="_blank" href="{{ route('privacy-policy') }}">Privacy Policy</a>
                <a target="_blank" href="{{ route('terms-of-service') }}">Terms of Service</a>
            </nav>
        </div>
    </div>
</footer>
@livewire('modal')
<livewire:toast />
@livewireScripts
</body>
<!-- Google tag (gtag.js) -->
<x-google-analytics />
</html>
