<div>
    @if($show)

        <div class="fixed inset-0 z-50 overflow-hidden flex items-center justify-center" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <!-- 背景遮罩 - 增加淡入淡出效果 -->
            <div class="absolute inset-0 bg-gray-900 bg-opacity-75 transition-opacity duration-300 ease-in-out backdrop-blur-sm"></div>

            <!-- 模态框内容 - 增加缩放和淡入效果 -->
            <div class="relative bg-white rounded-lg shadow-xl transform transition-all duration-300 ease-in-out sm:max-w-lg sm:w-full m-3 sm:m-6 scale-95 animate-modal">
                <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-semibold text-gray-900" id="modal-title">
                                {{ $title }}
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    {{ $content }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button
                        type="button"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm transition duration-150 ease-in-out transform hover:scale-105"
                        wire:click="hideModal"
                    >
                        关闭
                    </button>
                </div>
            </div>
        </div>

        @push('styles')
            <style>
                @keyframes modalAppear {
                    0% { opacity: 0; transform: scale(0.95); }
                    100% { opacity: 1; transform: scale(1); }
                }
                .animate-modal {
                    animation: modalAppear 0.3s ease-out forwards;
                }
            </style>
        @endpush
    @endif
</div>
