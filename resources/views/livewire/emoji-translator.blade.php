<div class="min-h-screen bg-gradient-to-r from-purple-400 via-pink-500 to-red-500 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-2xl p-8 max-w-2xl w-full space-y-8">
        <h1 class="text-4xl font-bold text-center text-gray-800 mb-6">🌈 Emoji Translator 🚀</h1>

        <div class="space-y-4">
            <textarea wire:model="prompt" wire:keydown.enter="generate" class="w-full h-32 p-4 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-purple-500 transition duration-300 ease-in-out" placeholder="Enter your text here..."></textarea>

            <button wire:click="generate" class="w-full bg-gradient-to-r from-purple-500 to-pink-500 text-white font-bold py-3 px-6 rounded-lg transform transition duration-300 ease-in-out hover:scale-105 focus:outline-none disabled:opacity-50 disabled:cursor-not-allowed" wire:loading.attr="disabled">
                Translate to Emoji 🎭
            </button>

            <div class="relative w-full h-40">
                <div wire:loading wire:target="generate" class="absolute inset-0 flex items-center justify-center bg-gray-100 rounded-lg">
                    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
                        <div class="animate-spin rounded-full h-16 w-16 border-t-4 border-b-4 border-purple-500"></div>
                    </div>
                </div>

                @if($generatedContent)
                    <div class="absolute inset-0 bg-gray-100 rounded-lg p-6 text-center flex flex-col justify-center">
                        <p class="text-4xl leading-relaxed break-words">{{ $generatedContent }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
