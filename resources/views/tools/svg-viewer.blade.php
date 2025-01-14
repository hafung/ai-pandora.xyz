@extends('layouts.tools-live', [
    'title' => __('seo.svg_viewer.title'),
    'description' => __('seo.svg_viewer.description'),
    'keywords' => __('seo.svg_viewer.keywords')
])


@section('content')

<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex flex-row space-y-6 md:space-y-0 md:space-x-6 gap-4">
            <div class="w-full md:w-1/2">
                <div class="mb-4">
                    <label for="svg-input" class="block text-sm font-medium text-gray-700 mb-2">Paste your SVG code here:</label>
                    <textarea id="svg-input" rows="17" class="w-full px-3 py-2 text-gray-700 border rounded-lg focus:outline-none focus:border-blue-500" placeholder="<svg>...</svg>"></textarea>
                </div>

                <div class="flex flex-wrap gap-2">
                    <button id="render-btn" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition duration-200">Render SVG</button>
                    <button id="download-svg-btn" class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition duration-200" disabled>Download SVG</button>
                    <button id="download-png-btn" class="px-4 py-2 bg-indigo-500 text-white rounded-lg hover:bg-indigo-700 transition duration-200" disabled>Download PNG</button>
                </div>
            </div>

            <div class="w-full md:w-1/2">
                <div id="svg-container" class="border-2 border-dashed border-gray-300 rounded-lg p-4 flex items-center justify-center h-full min-h-[300px]">
                    <p class="text-gray-500">Your SVG will appear here</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const svgInput = document.getElementById('svg-input');
            const renderBtn = document.getElementById('render-btn');
            const downloadSvgBtn = document.getElementById('download-svg-btn');
            const downloadPngBtn = document.getElementById('download-png-btn');
            const svgContainer = document.getElementById('svg-container');

            renderBtn.addEventListener('click', renderSVG);
            downloadSvgBtn.addEventListener('click', downloadSVG);
            downloadPngBtn.addEventListener('click', downloadPNG);

            function renderSVG() {
                const svgCode = svgInput.value.trim();
                if (svgCode) {
                    svgContainer.innerHTML = svgCode;
                    downloadSvgBtn.disabled = false;
                    downloadPngBtn.disabled = false;
                } else {
                    svgContainer.innerHTML = '<p class="text-gray-500">Please enter valid SVG code</p>';
                    downloadSvgBtn.disabled = true;
                    downloadPngBtn.disabled = true;
                }
            }

            function downloadSVG() {
                const svgCode = svgInput.value.trim();
                const blob = new Blob([svgCode], {type: "image/svg+xml;charset=utf-8"});
                downloadBlob(blob, "downloaded_image.svg");
            }

            function downloadPNG() {
                const svg = svgContainer.querySelector('svg');
                if (svg) {
                    const svgData = new XMLSerializer().serializeToString(svg);
                    const canvas = document.createElement("canvas");
                    const ctx = canvas.getContext("2d");
                    const img = new Image();
                    img.onload = function() {
                        canvas.width = img.width;
                        canvas.height = img.height;
                        ctx.drawImage(img, 0, 0);
                        canvas.toBlob(function(blob) {
                            downloadBlob(blob, "downloaded_image.png");
                        });
                    };
                    img.src = "data:image/svg+xml;base64," + btoa(unescape(encodeURIComponent(svgData)));
                }
            }

            function downloadBlob(blob, fileName) {
                const link = document.createElement('a');
                link.href = URL.createObjectURL(blob);
                link.download = fileName;
                link.click();
                URL.revokeObjectURL(link.href);
            }

            // Debounce the renderSVG function to improve performance
            const debouncedRenderSVG = _.debounce(renderSVG, 300);

            // Auto-render SVG as user types
            svgInput.addEventListener('input', debouncedRenderSVG);
        });
    </script>
@endpush
