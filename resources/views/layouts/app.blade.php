<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <x-seo-meta :title="$title ?? null" :full-title="$fullTitle ?? 'null'" :description="$description ?? null"
                :keywords="$keywords ?? null"/>
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    @livewireStyles
    @stack('styles')
    <script src="{{ mix('js/app.js') }}" defer></script>
</head>
<body class="bg-gray-100 min-h-screen">
@yield('header')
<main>
    {{ $slot }}
</main>
<x-footer/>
@livewire('modal')
<livewire:toast/>
@livewireScripts
@stack('scripts')
<x-google-analytics/>
</body>
</html>
