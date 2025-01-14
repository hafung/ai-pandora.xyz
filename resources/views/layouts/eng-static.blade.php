<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Learn English With AI')</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @stack('styles')
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&display=swap" rel="stylesheet">
    <style>
        @keyframes breathe {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        .logo-text {
            font-family: 'Orbitron', sans-serif;
            font-size: 1.5rem;
            font-weight: 700;
            color: #4A00E0;
            text-transform: uppercase;
            letter-spacing: 2px;
            position: relative;
            display: inline-block;
            padding: 5px 10px;
            background: linear-gradient(45deg, #4A00E0, #00E5FF);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-shadow: 0 0 10px rgba(74, 0, 224, 0.3);
            animation: breathe 3s ease-in-out infinite;
        }

        .logo-text::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, #4A00E0, #00E5FF);
            opacity: 0.3;
            filter: blur(10px);
            z-index: -1;
        }

        .logo-text::after {
            content: attr(data-text);
            position: absolute;
            top: 0;
            left: 0;
            z-index: -2;
            padding: 5px 10px;
            color: #00E5FF;
            filter: blur(4px);
        }

        .header-bg {
            background-color: #f0f4f8; /* 淡雅的浅蓝灰色 */
        }

    </style>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">
<header>
    <header class="header-bg shadow-sm">
        <div class="max-w-9xl mx-auto px-4 sm:px-6 lg:px-8 py-2 flex justify-between items-center">
            <div class="flex items-center">
                <div class="h-8 w-auto">
                    <span class="logo-text" data-text="AI英语卡片">AI英语卡片</span>
                </div>
            </div>
        </div>
    </header>
</header>

<main class="flex-grow container mx-auto px-4 py-8">
    @yield('content')
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
<x-google-analytics />
</body>
</html>
