<div class="min-h-screen bg-gradient-to-b from-gray-100 to-gray-200 py-12 px-4 sm:px-6 lg:px-8 flex flex-col">
    <div class="max-w-3xl w-full mx-auto bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="p-8">

            <div class="mb-6">
                <div class="mt-1 relative rounded-md shadow-sm">
                    <input
                        type="text"
                        wire:model.defer="prompt"
                        id="prompt"
                        class="focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 block w-full px-4 py-3 text-gray-700 bg-white border border-gray-300 rounded-md transition duration-150 ease-in-out"
                        placeholder="{{__('Enter any prompt to generate modern poetry. For example: spring, moonlight, love, delayed retirement, Mars...')}}"
                        wire:keydown.enter="generate"
                    >
                </div>
            </div>

            <div class="flex justify-center">
                <button
                    wire:click="generate"
                    class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out disabled:opacity-50 disabled:cursor-not-allowed"
                    wire:loading.attr="disabled"
                >
                    <span wire:loading.remove>{{__('Generate')}}</span>
                    <span wire:loading class="flex items-center">
                        {{__('Generating')}}
                    </span>
                </button>
            </div>
        </div>

        @if($generatedContent)
{{--            <div class="border-t border-gray-200">--}}
            <div class="mb-8">
{{--                <div class="p-8">--}}
{{--                    <div class="rounded-lg p-6">--}}
{{--                        <p class="text-gray-700 whitespace-pre-wrap leading-relaxed">{!! $generatedContent !!}</p>--}}
{{--                    </div>--}}
{{--                </div>--}}
                {!! $generatedContent !!}
            </div>
        @endif
    </div>
</div>
