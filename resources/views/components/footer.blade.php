{{--<footer class="mt-8 bg-gradient-to-b from-gray-50 to-white border-t border-gray-200">--}}
<footer class="bg-gradient-to-b from-gray-50 to-white border-t border-gray-200">
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col space-y-4 sm:flex-row sm:justify-between sm:items-center sm:space-y-0">
            <nav class="flex flex-wrap justify-center sm:justify-start space-x-6">
                <a target="_blank" href="{{ route('about') }}" class="text-gray-500 hover:text-gray-900 transition duration-300 ease-in-out text-sm">
                    {{__('About')}}
                </a>
                <a target="_blank" href="{{ route('privacy') }}" class="text-gray-500 hover:text-gray-900 transition duration-300 ease-in-out text-sm">
                    {{__('Privacy')}}
                </a>
                <a target="_blank" href="{{ route('terms') }}" class="text-gray-500 hover:text-gray-900 transition duration-300 ease-in-out text-sm">
                    {{__('Terms')}}
                </a>
            </nav>

            <div class="flex items-center justify-center sm:justify-end space-x-4">
                <div class="text-gray-400 text-sm">
                    © {{ date('Y') }} AI Pandora. {{__('All Rights Reserved')}}.
                </div>
                @if(!request()->routeIs('tools.index'))
                    <a target="_blank" href="{{ route('tools.index') }}" class="group inline-flex items-center text-blue-600 hover:text-blue-800 transition duration-300 ease-in-out text-sm">
                        {{__('Back To Toolkit')}}
                        <svg class="w-4 h-4 ml-1 transform group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                        </svg>
                    </a>
                @endif
            </div>
        </div>
    </div>
</footer>
