@extends('layouts.tools-live', [
    'title' => __('seo.txt_splitter.title'),
    'description' => __('seo.txt_splitter.description'),
    'keywords' => __('seo.txt_splitter.keywords')
])

@section('content')
    <div class="container mx-auto px-4 py-8">

        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="mb-4">
                <label for="input" class="block text-sm font-medium text-gray-700 mb-2">输入文本</label>
                <textarea id="input" rows="6" class="w-full px-3 py-2 text-gray-700 border rounded-lg focus:outline-none focus:border-blue-500" placeholder="请在此输入要处理的文本..."></textarea>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">选择操作</label>
                <div class="grid grid-cols-2 gap-4">
                    <button onclick="processText('newlineToComma')" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 px-4 rounded-lg transition duration-300 ease-in-out">
                        换行符转逗号
                    </button>
                    <button onclick="processText('commaToNewline')" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 px-4 rounded-lg transition duration-300 ease-in-out">
                        逗号转换行
                    </button>
                    <button onclick="processText('removeNewlines')" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 px-4 rounded-lg transition duration-300 ease-in-out">
                        去除所有换行
                    </button>
                    <button onclick="processText('removeSpaces')" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 px-4 rounded-lg transition duration-300 ease-in-out">
                        去除所有空格
                    </button>
                </div>
            </div>

            <div class="mb-4">
                <label for="output" class="block text-sm font-medium text-gray-700 mb-2">处理结果</label>
                <textarea id="output" rows="6" class="w-full px-3 py-2 text-gray-700 border rounded-lg focus:outline-none" readonly></textarea>
            </div>

            <div class="flex justify-end">
                <button onclick="copyToClipboard()" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg transition duration-300 ease-in-out">
                    复制结果
                </button>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        function processText(operation) {
            const input = document.getElementById('input').value;
            let result = '';

            switch (operation) {
                case 'newlineToComma':
                    result = input.replace(/\n/g, ',');
                    break;
                case 'commaToNewline':
                    result = input.replace(/,/g, '\n');
                    break;
                case 'removeNewlines':
                    result = input.replace(/\n/g, '');
                    break;
                case 'removeSpaces':
                    result = input.replace(/\s/g, '');
                    break;
            }

            document.getElementById('output').value = result;
        }

        function copyToClipboard() {
            const output = document.getElementById('output');
            output.select();
            document.execCommand('copy');

            // 显示复制成功提示
            const button = event.target;
            const originalText = button.textContent;
            button.textContent = '已复制!';
            button.classList.remove('bg-blue-500', 'hover:bg-blue-600');
            button.classList.add('bg-green-500', 'hover:bg-green-600');

            setTimeout(() => {
                button.textContent = originalText;
                button.classList.remove('bg-green-500', 'hover:bg-green-600');
                button.classList.add('bg-blue-500', 'hover:bg-blue-600');
            }, 2000);
        }
    </script>
@endpush
