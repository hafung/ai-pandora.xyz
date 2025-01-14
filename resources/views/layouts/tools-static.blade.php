<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Ai Pandora - 工具箱万花筒')</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">
<header>

</header>

<main class="flex-grow container mx-auto px-4 py-8">
    @yield('content')
</main>

<footer class="mt-8 bg-gradient-to-b from-gray-50 to-white border-t border-gray-200">
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col items-center space-y-4 sm:flex-row sm:justify-between sm:space-y-0">
            <nav class="flex flex-wrap justify-center space-x-6">
                <a target="_blank" href="{{ route('about') }}"
                   class="text-gray-500 hover:text-gray-900 transition duration-300 ease-in-out text-xs sm:text-sm">
                    {{__('About')}}
                </a>
                <a target="_blank" href="{{ route('privacy') }}"
                   class="text-gray-500 hover:text-gray-900 transition duration-300 ease-in-out text-xs sm:text-sm">
                    {{__('Privacy')}}
                </a>
                <a target="_blank" href="{{ route('terms') }}"
                   class="text-gray-500 hover:text-gray-900 transition duration-300 ease-in-out text-xs sm:text-sm">
                    {{__('Terms')}}
                </a>
            </nav>
            <div class="text-gray-400 text-xs font-light">
                © {{ date('Y') }} AI Pandora. {{__('All Rights Reserved')}}.
            </div>
        </div>
    </div>
</footer>
</body>
<!-- Google tag (gtag.js) -->
@if(config('services.google.tracking_id'))
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ config('services.google.tracking_id') }}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', '{{ config('services.google.tracking_id') }}');
    </script>
@endif
</html>
