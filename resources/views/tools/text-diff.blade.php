@extends('layouts.tools-live', [
    'title' => __('seo.txt_diff.title'),
    'description' => __('seo.txt_diff.description'),
    'keywords' => __('seo.txt_diff.keywords')
])

@section('content')
    <div class="container mx-auto px-4 py-8">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="text1" class="block text-sm font-medium text-gray-700 mb-2">{{__('Text')}} 1</label>
                <textarea id="text1" rows="18"
                          class="w-full px-3 py-2 text-gray-700 border rounded-lg focus:outline-none focus:border-blue-500"
                          placeholder="在此粘贴第一段文本..."></textarea>
            </div>
            <div>
                <label for="text2" class="block text-sm font-medium text-gray-700 mb-2">{{__('Text')}} 2</label>
                <textarea id="text2" rows="18"
                          class="w-full px-3 py-2 text-gray-700 border rounded-lg focus:outline-none focus:border-blue-500"
                          placeholder="在此粘贴第二段文本..."></textarea>
            </div>
        </div>

        <div class="mt-6 text-center">
            <button id="compareBtn"
                    class="px-6 py-2 bg-blue-500 text-white font-semibold rounded-lg shadow-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-opacity-75 transition-colors duration-200">
                {{__('Compare text')}}
            </button>
        </div>

        <div id="result" class="mt-8 p-4 bg-gray-100 rounded-lg shadow-inner hidden">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">{{__('Comparison results')}}</h2>
            <div id="diffOutput" class="text-sm font-mono"></div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/diff-match-patch/1.0.5/index.min.js" integrity="sha512-s/r2YIRA8VD7KT0c9uJqKrZFrNFgKlOPeLyVXp7noa6+F8vw5LMvR+hxteawjCpp6+5A4nTYoWtwLcXEJW1YzA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
        document.addEventListener('DOMContentLoaded', function () {
            const text1 = document.getElementById('text1');
            const text2 = document.getElementById('text2');
            const compareBtn = document.getElementById('compareBtn');
            const result = document.getElementById('result');
            const diffOutput = document.getElementById('diffOutput');

            compareBtn.addEventListener('click', function () {
                const diff = computeDiff(text1.value, text2.value);
                displayDiff(diff);
                result.classList.remove('hidden');
            });

            function computeDiff(text1, text2) {
                const dmp = new diff_match_patch();
                const diffs = dmp.diff_main(text1, text2);
                dmp.diff_cleanupSemantic(diffs);
                return diffs;
            }

            function displayDiff(diffs) {
                let html = '';
                let lineNumber1 = 1;
                let lineNumber2 = 1;
                let currentLine = '';

                diffs.forEach(([type, text]) => {
                    const lines = text.split('\n');
                    lines.forEach((line, index) => {
                        if (index > 0) {
                            html += formatLine(currentLine, lineNumber1, lineNumber2);
                            currentLine = '';
                            if (type !== -1) lineNumber2++;
                            if (type !== 1) lineNumber1++;
                        }

                        let lineClass = '';
                        let prefix = '';
                        switch (type) {
                            case 1:  // 插入
                                lineClass = 'text-green-600 bg-green-100';
                                prefix = '+';
                                break;
                            case -1:  // 删除
                                lineClass = 'text-red-600 bg-red-100';
                                prefix = '-';
                                break;
                            case 0:  // 相同
                                lineClass = 'text-gray-800';
                                prefix = ' ';
                                break;
                        }
                        currentLine += `<span class="${lineClass}">${prefix}${_.escape(line)}</span>`;
                    });
                });

                if (currentLine) {
                    html += formatLine(currentLine, lineNumber1, lineNumber2);
                }

                diffOutput.innerHTML = html;
            }

            function formatLine(content, lineNumber1, lineNumber2) {
                return `<div class="flex">
                    <span class="w-12 inline-block text-right pr-2 text-gray-500">${lineNumber1}</span>
                    <span class="w-12 inline-block text-right pr-2 text-gray-500">${lineNumber2}</span>
                    <span class="flex-grow">${content}</span>
                </div>`;
            }
        });
    </script>
@endpush
