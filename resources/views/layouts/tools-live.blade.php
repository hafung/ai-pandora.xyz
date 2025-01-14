<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <x-seo-meta :title="$title ?? null" :full-title="$fullTitle ?? null" :description="$description ?? null"
                :keywords="$keywords ?? null"/>
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    @livewireStyles
    @stack('styles')
    <script src="{{ mix('js/app.js') }}" defer></script>
</head>
<body class="bg-gray-100 min-h-screen">
<header class="shadow-sm">
    @yield('header')
</header>
<main>
    @hasSection('content')
        @yield('content')
    @else
        {{ $slot ?? '' }}
    @endif
</main>
<x-footer/>
<div id="toast-container" class="fixed top-4 left-1/2 transform -translate-x-1/2 z-50"></div>
@include('components.loading')
<livewire:toast/>
@livewireScripts
@stack('scripts')
<x-google-analytics/>
</body>
</html>
