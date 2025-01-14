<div class="mx-auto h-screen flex flex-col bg-gradient-to-br from-gray-900 to-gray-800 rounded-lg shadow-2xl overflow-hidden">
    <!-- 顶部区域 -->
    <div class="flex-1 p-8">
        <h2 class="text-3xl font-bold mb-6 text-center text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-purple-500 flex items-center justify-center">
            {{__('Focus on improving the quality of questions')}}
            <svg class="w-8 h-8 ml-2 inline-block" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <defs>
                    <linearGradient id="icon-gradient" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" style="stop-color:#60A5FA;" />
                        <stop offset="100%" style="stop-color:#A78BFA;" />
                    </linearGradient>
                </defs>
                <path fill="url(#icon-gradient)" fill-rule="evenodd" d="M10.5 3.75a6.75 6.75 0 100 13.5 6.75 6.75 0 000-13.5zM2.25 10.5a8.25 8.25 0 1114.59 5.28l4.69 4.69a.75.75 0 11-1.06 1.06l-4.69-4.69A8.25 8.25 0 012.25 10.5z" clip-rule="evenodd" />
            </svg>
        </h2>
        <div class="bg-gray-800 bg-opacity-50 rounded-xl p-6 backdrop-filter backdrop-blur-lg">
            <textarea wire:model.defer="question" wire:keydown.enter="sendMessage" id="userInput" rows="4" class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-200" placeholder="{{__('Please tell me the question you want to improve')}}..."></textarea>
        </div>
    </div>

    <!-- 中间区域 -->
    <div class="py-4 px-8 flex justify-center items-center">
        <button wire:click="sendMessage" wire:loading.attr="disabled" class="relative inline-flex items-center justify-center w-48 h-12 px-6 overflow-hidden font-medium text-white bg-gradient-to-r from-blue-500 to-purple-600 rounded-full shadow-lg transition-all duration-300 ease-out hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed group">
            <span class="absolute inset-0 w-full h-full bg-gradient-to-br from-blue-500 to-purple-600 opacity-0 group-hover:opacity-100 transition-opacity duration-300 ease-out"></span>
            <span class="relative flex items-center justify-center w-full transition-all duration-300 ease-out group-hover:scale-95">
            <span wire:loading.remove class="text-sm font-semibold">{{__('Send')}}</span>
            <span wire:loading class="flex items-center space-x-2">
                <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </span>
        </span>
        </button>
    </div>

    <!-- 底部区域 -->
    <div class="flex-1 p-8">
        <div class="bg-gray-800 bg-opacity-50 rounded-xl p-6 backdrop-filter backdrop-blur-lg min-h-[200px] relative">
                <p class="text-gray-300">{{ $aiResponse }}</p>
        </div>
    </div>
</div>
