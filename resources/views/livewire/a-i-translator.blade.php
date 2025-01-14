@section('header')
    <div class="bg-gray-50 px-4 py-6">
        <h1 class="text-2xl font-bold text-gray-800 inline-flex items-center">
            AI·智能翻译
            <span class="text-sm font-normal text-gray-600 italic ml-4">
                自动中翻英、英翻中 😊
            </span>
        </h1>
    </div>
@endsection


<div class="container w-full p-4 mx-auto  h-screen">
    <!-- 左侧输入区 -->
{{--    <div class="left-column h-full">--}}
    <div class="left-column h-5/6">
        <textarea
            wire:model.defer="inputText"
            class="w-full h-full p-4 text-lg bg-white border-2 border-gray-300 rounded-lg shadow-inner focus:outline-none focus:border-blue-500 transition duration-300 ease-in-out resize-none"
            placeholder="请输入要翻译的文本..."
        ></textarea>
    </div>

    <!-- 中间按钮区 -->
{{--    <div class="middle-column h-full mx-auto flex items-center justify-center">--}}
    <div class=" h-5/6 mx-auto flex items-center justify-center">
        <button
            wire:click="translate"
            wire:loading.class="opacity-50 cursor-not-allowed"
            wire:loading.attr="disabled"
            class="w-auto px-6 py-3 bg-gradient-to-r bg-blue-400 text-white rounded-full shadow-lg hover:from-blue-600 hover:to-cyan-600 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-opacity-50 transition duration-300 ease-in-out transform hover:scale-105"
        >
            <span class="flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M7 2a1 1 0 011 1v1h3a1 1 0 110 2H9.578a18.87 18.87 0 01-1.724 4.78c.29.354.596.696.914 1.026a1
1 0 0 11-1.44 1.389 21.034 21.034 0 01-.554-.6 19.098 19.098 0 01-3.107 3.567 1 1 0 01-1.334-1.49 17.087 17.087 0 003.13-3.733 18.992 18.992 0 01-1.487-2.494 1 1 0 111.79-.89c.234.47.489.928.764 1.372.417-.934.752-1.913.997-2.927H3a1 1 0 110-2h3V3a1 1 0 011-1zm6 6a1 1 0 01.894.553l2.991 5.982a.869.869 0 01.02.037l.99 1.98a1 1 0 11-1.79.895L15.383 16h-4.764l-.724 1.447a1 1 0 11-1.788-.894l.99-1.98.019-.038 2.99-5.982A1 1 0 0113 8zm-1.382 6h2.764L13 11.236 11.618 14z" clip-rule="evenodd" />
        </svg>
                翻译
            </span>
        </button>
    </div>

    <!-- 右侧输出区 -->
{{--    <div class="right-column h-full">--}}
    <div class="right-column h-5/6">
        <div class="w-full h-full bg-white border-2 border-gray-300 rounded-lg shadow-inner p-4 overflow-auto">
                <p class="text-lg">{{ $translatedText }}</p>
        </div>
    </div>
</div>

@push('styles')

<style>
    .container {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        align-items: stretch;
    }

    .left-column {
        flex-basis: 43%;
    }

    /*.middle-column {*/
    /*    flex-basis: 14%;*/
    /*    background-color: #eee;*/
    /*}*/

    .right-column {
        flex-basis: 43%;
        background-color: #ccc;
    }
    </style>
@endpush
