{{--<div id="global-loading" class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-50 hidden">--}}
{{--    <div class="animate-spin rounded-full h-32 w-32 border-t-2 border-b-2 border-blue-500"></div>--}}
{{--</div>--}}

{{--<div id="loading-mask" class="fixed inset-0 bg-gray-900 bg-opacity-50 z-50 flex items-center justify-center hidden">--}}
{{--    <div class="animate-spin rounded-full h-32 w-32 border-t-2 border-b-2 border-blue-500"></div>--}}
{{--</div>--}}

{{--@once--}}
{{--    @push('scripts')--}}
{{--        <script>--}}
{{--            document.addEventListener('DOMContentLoaded', function() {--}}
{{--                window.LoadingMask = {--}}
{{--                    show: function() {--}}
{{--                        document.getElementById('loading-mask').classList.remove('hidden');--}}
{{--                    },--}}
{{--                    hide: function() {--}}
{{--                        document.getElementById('loading-mask').classList.add('hidden');--}}
{{--                    }--}}
{{--                };--}}

{{--                // 如果使用了 Lodash，可以添加防抖--}}
{{--                if (typeof _ !== 'undefined') {--}}
{{--                    window.LoadingMask.debouncedHide = _.debounce(window.LoadingMask.hide, 300);--}}
{{--                }--}}
{{--            });--}}
{{--        </script>--}}
{{--    @endpush--}}
{{--@endonce--}}

{{--样式不好看--}}
{{--<div id="loading-mask" class="fixed inset-0 bg-gray-900 bg-opacity-50 z-50 flex items-center justify-center hidden">--}}
{{--    <div class="animate-spin rounded-full h-32 w-32 border-t-2 border-b-2 border-blue-500"></div>--}}
{{--</div>--}}

{{--@once--}}
{{--    @push('scripts')--}}
{{--        <script>--}}
{{--            (function() {--}}
{{--                var LoadingMask = {--}}
{{--                    show: function() {--}}
{{--                        document.getElementById('loading-mask').classList.remove('hidden');--}}
{{--                    },--}}
{{--                    hide: function() {--}}
{{--                        document.getElementById('loading-mask').classList.add('hidden');--}}
{{--                    }--}}
{{--                };--}}

{{--                if (typeof _ !== 'undefined') {--}}
{{--                    LoadingMask.debouncedHide = _.debounce(LoadingMask.hide, 300);--}}
{{--                }--}}

{{--                // 使 LoadingMask 在全局范围内可用--}}
{{--                window.LoadingMask = LoadingMask;--}}
{{--            })();--}}
{{--        </script>--}}
{{--    @endpush--}}
{{--@endonce--}}

{{--好看多了！！--}}
{{--<div id="loading-mask" class="fixed inset-0 bg-gray-900 bg-opacity-50 backdrop-blur-sm z-50 flex items-center justify-center hidden transition-opacity duration-300">--}}
{{--    <div class="bg-white bg-opacity-90 rounded-lg p-8 shadow-xl">--}}
{{--        <div class="flex flex-col items-center">--}}
{{--            <div class="w-16 h-16 relative">--}}
{{--                <div class="w-16 h-16 rounded-full border-t-4 border-b-4 border-blue-500 animate-spin"></div>--}}
{{--                <div class="w-16 h-16 rounded-full border-r-4 border-l-4 border-blue-300 animate-spin absolute top-0 left-0 animate-ping"></div>--}}
{{--            </div>--}}
{{--            <p class="mt-4 text-gray-700 font-semibold text-lg">加载中...</p>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</div>--}}

{{--@once--}}
{{--    @push('scripts')--}}
{{--        <script>--}}
{{--            (function() {--}}
{{--                var LoadingMask = {--}}
{{--                    show: function() {--}}
{{--                        document.getElementById('loading-mask').classList.remove('hidden');--}}
{{--                        setTimeout(() => {--}}
{{--                            document.getElementById('loading-mask').classList.add('opacity-100');--}}
{{--                        }, 10);--}}
{{--                    },--}}
{{--                    hide: function() {--}}
{{--                        document.getElementById('loading-mask').classList.remove('opacity-100');--}}
{{--                        setTimeout(() => {--}}
{{--                            document.getElementById('loading-mask').classList.add('hidden');--}}
{{--                        }, 300);--}}
{{--                    }--}}
{{--                };--}}

{{--                if (typeof _ !== 'undefined') {--}}
{{--                    LoadingMask.debouncedHide = _.debounce(LoadingMask.hide, 300);--}}
{{--                }--}}

{{--                window.LoadingMask = LoadingMask;--}}
{{--            })();--}}
{{--        </script>--}}
{{--    @endpush--}}
{{--@endonce--}}


@php
    $defaultLoadingText = '加载中...';
@endphp

<div id="loading-mask" class="fixed inset-0 bg-gray-900 bg-opacity-50 backdrop-blur-sm z-50 flex items-center justify-center hidden transition-opacity duration-300">
    <div class="bg-white bg-opacity-90 rounded-lg p-8 shadow-xl">
        <div class="flex flex-col items-center">
            <div class="w-16 h-16 relative">
                <div class="w-16 h-16 rounded-full border-t-4 border-b-4 border-blue-500 animate-spin"></div>
                <div class="w-16 h-16 rounded-full border-r-4 border-l-4 border-blue-300 animate-spin absolute top-0 left-0 animate-ping"></div>
            </div>
            <p id="loading-text" class="mt-4 text-gray-700 font-semibold text-lg">{{ $defaultLoadingText }}</p>
        </div>
    </div>
</div>

@once
    @push('scripts')
        <script>
            (function() {
                var LoadingMask = {
                    show: function(text) {
                        if (text) {
                            this.setText(text);
                        }
                        document.getElementById('loading-mask').classList.remove('hidden');
                        setTimeout(() => {
                            document.getElementById('loading-mask').classList.add('opacity-100');
                        }, 10);
                    },
                    hide: function() {
                        document.getElementById('loading-mask').classList.remove('opacity-100');
                        setTimeout(() => {
                            document.getElementById('loading-mask').classList.add('hidden');
                            this.setText('{{ $defaultLoadingText }}');
                        }, 300);
                    },
                    setText: function(text) {
                        document.getElementById('loading-text').textContent = text;
                    }
                };

                if (typeof _ !== 'undefined') {
                    LoadingMask.debouncedHide = _.debounce(LoadingMask.hide, 300);
                }

                window.LoadingMask = LoadingMask;
            })();
        </script>
    @endpush
@endonce
