@extends('layouts.tools-live', [
    'title' => __('seo.json_beautifier.title'),
    'description' => __('seo.json_beautifier.description'),
    'keywords' => __('seo.json_beautifier.keywords')
])

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jsoneditor/10.1.0/jsoneditor.css"
          integrity="sha512-iOFdnlwX6UGb55bU5DL0tjWkS/+9jxRxw2KiRzyHMZARASUSwm0nEXBcdqsYni+t3UKJSK7vrwvlL8792/UMjQ=="
          crossorigin="anonymous" referrerpolicy="no-referrer"/>
@endpush

@section('content')
    <div class="max-w-7xl mx-auto px-4 py-8">

        <div class="flex space-x-6 min-h-[600px]">
            <div class="w-1/2 flex-1 shrink-0">

                <div class="mb-4 flex justify-end space-x-4">
                    <button id="beautifyBtn"
                            class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition duration-150 ease-in-out">
                        {{__('Beautifier')}}
                    </button>
                    <button id="compressBtn"
                            class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-opacity-50 transition duration-150 ease-in-out">
                        {{__('Minifier')}}
                    </button>
                </div>

                <textarea id="jsonInput" rows="22"
                          class="flex-grow w-full px-3 py-2 text-gray-700 border rounded-lg focus:outline-none focus:border-blue-500 resize-none overflow-auto"
                          placeholder="{{__('Please enter or paste JSON string')}}..."></textarea>
            </div>

            <div class="w-1/2 flex-1 flex flex-col">
                <div id="jsonOutput" class="flex-grow bg-gray-100 rounded-lg overflow-auto p-4"></div>
            </div>
        </div>
    </div>

@endsection


@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jsoneditor/10.1.0/jsoneditor.min.js"
            integrity="sha512-PInE2t9LrzM/U5c/sB27ZCv/thNDKIA1DgRBzOcvaq21qlnQ/yI/YvzJMLdzsM1MvmX9j4TQLFi8+2+rTkdR4w=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>


        document.addEventListener('DOMContentLoaded', function () {
            const jsonInput = document.getElementById('jsonInput');
            const beautifyBtn = document.getElementById('beautifyBtn');
            const compressBtn = document.getElementById('compressBtn');
            const jsonOutput = document.getElementById('jsonOutput');

            const options = {
                mode: 'view',
                modes: ['tree', 'view', 'form', 'code', 'text', 'preview'],
            };

            const editor = new JSONEditor(jsonOutput, options);

            function parseJSON(str) {
                try {
                    // 首先尝试直接解析
                    return JSON.parse(str);
                } catch (e) {
                    try {
                        // 如果直接解析失败，尝试解析转义后的JSON
                        return JSON.parse(JSON.parse('"' + str.replace(/"/g, '\\"') + '"'));
                    } catch (e) {
                        try {
                            // 如果上述方法都失败，尝试替换转义字符后再解析
                            return JSON.parse(str.replace(/\\/g, ''));
                        } catch (e) {
                            return null;
                        }
                    }
                }
            }

            function beautifyJSON(json) {
                return JSON.stringify(json, null, 2);
            }

            function compressJSON(json) {
                return JSON.stringify(json);
            }


            function updateOutput(json, action) {
                if (action === 'beautify') {
                    editor.set(json);
                    editor.expandAll();
                } else {
                    editor.set(json);
                    editor.collapseAll();
                }
            }

            function processJSON(action) {
                const input = jsonInput.value.trim();
                const parsedJSON = parseJSON(input);

                if (parsedJSON) {
                    updateOutput(parsedJSON, action);
                } else {
                    showToast('无效的JSON格式，请检查输入。', 'error');
                }
            }

            // 自动美化功能
            function autoBeautify() {
                const input = jsonInput.value.trim();
                if (input) {
                    processJSON('beautify');
                } else {
                    editor.set({});
                }
            }

            jsonInput.addEventListener('input', _.debounce(autoBeautify, 500));

            beautifyBtn.addEventListener('click', () => processJSON('beautify'));
            compressBtn.addEventListener('click', () => processJSON('compress'));

            autoBeautify();
        });
    </script>
@endpush
