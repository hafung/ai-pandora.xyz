<div class="max-w-3xl mx-auto px-4 py-8">
    <h1 class="text-3xl font-semibold text-gray-800 mb-6">{{__('Optimistic Magician')}}</h1>

    <div class="bg-white shadow-lg rounded-lg p-6 mb-8">
        <form wire:submit.prevent="generate" class="space-y-4">
            <div>
                <label for="userInput" class="block text-sm font-medium text-gray-700 mb-2">
                </label>
                <textarea
                    id="userInput"
                    wire:model.defer="prompt"
                    wire:keydown.enter="generate"
                    rows="4"
                    class="w-full px-3 py-2 text-gray-700 border rounded-lg focus:outline-none focus:border-blue-500"
                    placeholder="{{__('Tell me any complaints or frustrations you have')}}..."
                ></textarea>
            </div>
            <div>
                <button
                    type="submit"
                    class="w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg transition duration-300 ease-in-out transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50"
                    wire:loading.attr="disabled"
                    wire:loading.class="opacity-75 cursor-not-allowed"
                >
                    {{__('Get a fresh perspective')}}
                    <span wire:loading wire:target="getAdvice" class="ml-2">
                        <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </span>
                </button>
            </div>
        </form>
    </div>

    @if($generatedContent)
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-6 rounded-lg shadow-md">
            <p class="text-lg text-gray-700 italic">
                "{{ $generatedContent }}"
            </p>
        </div>
    @endif
</div>
