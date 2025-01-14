@extends('layouts.tools-live', [
    'fullTitle' => __('seo.index.full_title'),
    'description' => __('seo.index.description'),
    'keywords' => __('seo.index.keywords')
])

@section('header')
    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 h-32 relative overflow-hidden">
        <div class="absolute inset-0 bg-pattern opacity-10"></div>

        <div class="container mx-auto px-4 sm:px-6 lg:px-8 h-full relative z-10">

            <div class="flex justify-between items-center h-full">
                <div class="flex items-center space-x-4">
                    <h1 class="text-4xl sm:text-5xl font-extrabold relative group">
                    <span
                        class="bg-clip-text text-transparent bg-gradient-to-r from-white to-purple-200 group-hover:from-purple-200 group-hover:to-white transition-all duration-300">
                        Pandora Toolkit
                    </span>
                        <span
                            class="absolute -bottom-1 left-0 w-full h-1 bg-white transform scale-x-0 transition-transform duration-300 origin-left group-hover:scale-x-100"></span>
                    </h1>
                    <p class="ml-4 text-sm text-purple-100 hidden sm:block font-medium">
                        {{__('Efficient and Practical Online Toolkit')}}
                    </p>
                </div>
                <div class="flex-shrink-0 animate-float">
                    <svg width="120" height="120" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg"
                         class="text-white">
                        <defs>
                            <linearGradient id="grad" x1="0%" y1="0%" x2="100%" y2="100%">
                                <stop offset="0%" style="stop-color:#4a90e2;stop-opacity:1"/>
                                <stop offset="100%" style="stop-color:#9b59b6;stop-opacity:1"/>
                            </linearGradient>
                        </defs>

                        <!-- Toolbox -->
                        <rect x="20" y="60" width="160" height="120" rx="10" ry="10" fill="url(#grad)"/>
                        <rect x="20" y="60" width="160" height="30" rx="10" ry="10" fill="#2c3e50"/>

                        <!-- AI text -->
                        <text x="100" y="130" font-family="Arial, sans-serif" font-size="60" fill="white"
                              text-anchor="middle">AI
                        </text>

                        <!-- Pandora text -->
                        <text x="100" y="170" font-family="Arial, sans-serif" font-size="24" fill="white"
                              text-anchor="middle">Pandora
                        </text>

                        <!-- Circuit board lines -->
                        <path d="M30 45 L50 45 L50 25 M170 45 L150 45 L150 25" stroke="white" stroke-width="2"
                              fill="none"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('content')
    <div
        class="container px-4 sm:px-6 lg:px-8 pt-8 pb-16 relative w-full {{$category === 'all' ? ' mx-auto' : ''}}">

        <div class="absolute inset-0 bg-gradient-to-b from-purple-50 to-indigo-50 opacity-50"></div>
        <div class="relative z-10">
            <div class="flex flex-col lg:flex-row gap-8 justify-center">
            @if($category != 'all')
                <!-- 左侧菜单 -->
                    <div class="lg:w-1/5">
                        <nav
                            class="bg-white rounded-2xl shadow-lg overflow-hidden backdrop-filter backdrop-blur-lg bg-opacity-80">
                            <ul class="space-y-1">
                                @foreach($categories as $key => $name)
                                    <li class="relative {{$key === 'ai' ? 'featured' : ''}}">
                                        <a href="{{ route('tools.index', ['category' => $key]) }}"
                                           class="block px-6 py-4 text-lg font-medium transition-all duration-300 ease-in-out relative overflow-hidden group
                                   {{ ($category == $key || (!$category && $key === 'recommend')) ? 'text-white' : 'text-gray-700 hover:text-white' }}">
                                            <span class="relative z-10">{{ $name }}</span>
                                            <span class="absolute inset-0 bg-gradient-to-r from-indigo-500 to-purple-500 opacity-100 transform
                                    {{ ($category == $key || (!$category && $key === 'recommend')) ? 'translate-x-0' : 'translate-x-full' }}
                                                transition-transform duration-300 ease-in-out group-hover:translate-x-0"></span>
                                            <span
                                                class="absolute left-0 top-0 h-full w-1 bg-indigo-500 transform scale-y-0 transition-transform duration-300 origin-top group-hover:scale-y-100"></span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </nav>
                    </div>
            @endif

            <!-- 右侧工具列表 -->
                <div class="{{ $category == 'all' ? 'w-full' : 'lg:w-4/5' }}">
                    <div
                        class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 {{ $category == 'all' ? 'xl:grid-cols-5 2xl:grid-cols-6' : 'xl:grid-cols-4 2xl:grid-cols-5' }}  gap-6">
                        @foreach($displayTools as $tool)
                            <a href="{{ isset($tool['route']) ? route($tool['route']) : $tool['url']}}" target="_blank"
                               rel="noopener noreferrer"
                               class="block group {{empty($tool['featured'])  ? '' : 'featured'}}">
                                <div
                                    class="bg-white bg-opacity-80 backdrop-filter backdrop-blur-lg rounded-xl shadow-lg overflow-hidden transform transition duration-300 ease-in-out hover:scale-105 hover:shadow-xl border-2 border-transparent hover:border-indigo-300 flex flex-col h-full">
                                    <div
                                        class="p-6 flex flex-col items-center justify-center h-full relative overflow-hidden">
                                        <div
                                            class="absolute inset-0 bg-gradient-to-br from-indigo-500 to-purple-500 opacity-0 group-hover:opacity-10 transition-opacity duration-300"></div>
                                        <div
                                            class="w-16 h-16 bg-gradient-to-br from-indigo-400 to-purple-400 text-white rounded-full flex items-center justify-center mb-4 transform transition-transform duration-300 group-hover:rotate-12 relative z-10">
                                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                 xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="{{$tool['icon'] ?? ''}}"></path>
                                            </svg>
                                        </div>
                                        <div class="flex flex-col items-center justify-center flex-grow relative z-10">
                                            <h3 class="text-xl font-semibold text-gray-800 mb-2 text-center group-hover:text-indigo-600 transition-colors duration-300">{{ $tool['name'] }}</h3>
                                            <p class="text-gray-600 text-center group-hover:text-indigo-500 transition-colors duration-300">{{ $tool['description'] }}</p>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('styles')
    <style>
        .animate-float {
            animation: float 3s ease-in-out infinite;
        }

        .bg-pattern {
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.4'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
        @keyframes rainbow-border {
            0%, 100% {
                border-color: #ff0000;
            }
            14% {
                border-color: #ff7f00;
            }
            28% {
                border-color: #ffff00;
            }
            42% {
                border-color: #00ff00;
            }
            57% {
                border-color: #0000ff;
            }
            71% {
                border-color: #8b00ff;
            }
            85% {
                border-color: #ff00ff;
            }
        }

        .featured {
            position: relative;
            z-index: 1;
        }

        .featured::before {
            content: '';
            position: absolute;
            top: -3px;
            left: -3px;
            right: -3px;
            bottom: -3px;
            background: linear-gradient(45deg, #ff0000, #ff7f00, #ffff00, #00ff00, #0000ff, #8b00ff, #ff00ff);
            border-radius: inherit;
            z-index: -1;
            animation: rainbow-border 5s linear infinite;
            filter: blur(5px);
            opacity: 0.7;
        }

        .featured:hover::before {
            filter: blur(3px);
            opacity: 1;
        }

        /* 为菜单项调整样式 */
        .featured > a {
            border: none !important;
        }

        /* 为工具格子调整样式 */
        .featured > div {
            border: none !important;
            background: rgba(255, 255, 255, 0.9) !important;
        }
    </style>
@endpush
