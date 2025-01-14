@extends('layouts.tools-live', [
    'title' => __('seo.image_converter.title'),
    'description' => __('seo.image_converter.description'),
    'keywords' => __('seo.image_converter.keywords')
])

@push('styles')
    <style>
        #result .flex {
            align-items: flex-end;
        }

        .download-link {
            margin-bottom: 4px;
        }

        .output-image {
            margin-top: 8px;
        }

        .preview-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 16px;
            margin-top: 16px;
        }

        .preview-item {
            position: relative;
            border: 1px solid #ddd;
            padding: 8px;
            border-radius: 4px;
        }

        .preview-item img {
            width: 100%;
            height: auto;
        }

        .remove-btn {
            position: absolute;
            top: -8px;
            right: -8px;
            background: #ff4444;
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 12px;
        }

        .progress-bar {
            width: 100%;
            height: 4px;
            background-color: #f0f0f0;
            border-radius: 2px;
            margin-top: 8px;
        }

        .progress-bar-fill {
            height: 100%;
            background-color: #4CAF50;
            border-radius: 2px;
            width: 0%;
            transition: width 0.3s ease;
        }

        .download-all-btn {
            margin-left: 8px;
        }

        #customSizeInputs {
            transition: all 0.3s ease;
        }
        #customSizeInputs.hidden {
            display: none;
        }
        #customSizeInputs input[type="number"] {
            -moz-appearance: textfield;
        }
        #customSizeInputs input[type="number"]::-webkit-outer-spin-button,
        #customSizeInputs input[type="number"]::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
    </style>
@endpush

@section('content')
    <div class="container mx-auto px-4 py-8 min-h-screen">
        <h1 class="text-3xl font-bold mb-6">批量图片格式转换</h1>

        <div class="bg-white shadow-md rounded-lg p-6">
            <div class="mb-4">
                <label for="imageInput" class="block text-gray-700 font-bold mb-2">选择图片（最多20张）</label>
                <input type="file" id="imageInput" accept="image/*" multiple class="w-full p-2 border rounded">
                <div id="previewContainer" class="preview-container"></div>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="formatSelect" class="block text-gray-700 font-bold mb-2">目标格式</label>
                    <select id="formatSelect" class="w-full p-2 border rounded">
                        <option value="png">PNG</option>
                        <option value="jpeg">JPEG</option>
                        <option value="webp">WebP</option>
                        <option value="ico">ICO</option>
                    </select>
                </div>
            {{--                <div>--}}
            {{--                    <label for="sizeInput" class="block text-gray-700 font-bold mb-2">输出尺寸 (可选)</label>--}}
            {{--                    <input type="text" id="sizeInput" placeholder="例如: 100x100" class="w-full p-2 border rounded">--}}
            {{--                </div>--}}

            <!-- 将原来的 sizeInput div 替换为以下代码 -->
                <div>
                    <label for="sizeSelect" class="block text-gray-700 font-bold mb-2">输出尺寸</label>
                    <div class="flex gap-4">
                        <select id="sizeSelect" class="w-full p-2 border rounded">
                            <option value="original">保持原始尺寸</option>
                            <option value="16:9">16:9</option>
                            <option value="9:16">9:16</option>
                            <option value="custom">自定义尺寸</option>
                        </select>
                        <div id="customSizeInputs" class="hidden flex gap-2">
                            <input type="number" id="widthInput" placeholder="宽度" class="w-24 p-2 border rounded">
                            <span class="flex items-center">×</span>
                            <input type="number" id="heightInput" placeholder="高度" class="w-24 p-2 border rounded">
                        </div>
                    </div>
                </div>

            </div>

            <div class="flex">
                <button id="convertBtn" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                    转换图片
                </button>
                <button id="downloadAllBtn"
                        class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded download-all-btn hidden">
                    下载全部
                </button>
            </div>

            <div id="result" class="mt-6">
                <div id="progressContainer" class="hidden">
                    <h3 class="text-lg font-semibold mb-2">转换进度</h3>
                    <div class="progress-bar">
                        <div class="progress-bar-fill"></div>
                    </div>
                </div>
                <div id="convertedImages" class="preview-container"></div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>

    <script>

        document.addEventListener('DOMContentLoaded', function () {
            const imageInput = document.getElementById('imageInput');
            const formatSelect = document.getElementById('formatSelect');

            // const sizeInput = document.getElementById('sizeInput');
            const sizeSelect = document.getElementById('sizeSelect');
            const customSizeInputs = document.getElementById('customSizeInputs');
            const widthInput = document.getElementById('widthInput');
            const heightInput = document.getElementById('heightInput');

            const convertBtn = document.getElementById('convertBtn');
            const downloadAllBtn = document.getElementById('downloadAllBtn');
            const previewContainer = document.getElementById('previewContainer');
            const convertedImages = document.getElementById('convertedImages');
            const progressContainer = document.getElementById('progressContainer');
            const progressBarFill = document.querySelector('.progress-bar-fill');

            let selectedFiles = [];
            let convertedFiles = [];

            imageInput.addEventListener('change', handleFileSelect);
            convertBtn.addEventListener('click', convertImages);
            downloadAllBtn.addEventListener('click', downloadAllImages);

            // 添加尺寸选择变化监听
            sizeSelect.addEventListener('change', function() {
                if (this.value === 'custom') {
                    customSizeInputs.classList.remove('hidden');
                } else {
                    customSizeInputs.classList.add('hidden');
                }
            });

            function handleFileSelect(e) {
                // const files = Array.from(e.target.files);
                // if (files.length > 20) {
                //     showToast('最多只能选择20张图片', 'error');
                //     imageInput.value = '';
                //     return;
                // }
                //
                // selectedFiles = files;
                // updatePreview();

                const newFiles = Array.from(e.target.files);
                const totalFiles = [...selectedFiles, ...newFiles];

                // 检查文件数量限制
                if (totalFiles.length > 20) {
                    showToast('最多只能选择20张图片', 'error');
                    imageInput.value = ''; // 清空当前选择
                    return;
                }

                // 更新选择的文件
                selectedFiles = totalFiles;

                // 更新预览
                updatePreview();

                // 分析文件格式并设置转换格式
                updateTargetFormat();

                // 清空文件输入框，以便能够重复选择同一文件
                imageInput.value = '';
            }

            function updatePreview() {
                previewContainer.innerHTML = '';
                // selectedFiles.forEach((file, index) => {
                //     const reader = new FileReader();
                //     reader.onload = function(e) {
                //         const previewItem = createPreviewItem(e.target.result, index);
                //         previewContainer.appendChild(previewItem);
                //     }
                //     reader.readAsDataURL(file);
                // });
                selectedFiles.forEach((file, index) => {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        const previewDiv = document.createElement('div');
                        previewDiv.className = 'preview-item relative';
                        previewDiv.innerHTML = `
                <img src="${e.target.result}" class="preview-image" alt="Preview">
                <button class="delete-btn absolute top-0 right-0 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center"
                        onclick="removeFile(${index})">×</button>
            `;
                        previewContainer.appendChild(previewDiv);
                    };
                    reader.readAsDataURL(file);
                });
            }

            function createPreviewItem(src, index) {
                const div = document.createElement('div');
                div.className = 'preview-item';
                div.innerHTML = `
                    <img src="${src}" alt="Preview">
                    <div class="remove-btn" onclick="removeFile(${index})">×</div>
                `;
                return div;
            }

            window.removeFile = function (index) {
                // selectedFiles = selectedFiles.filter((_, i) => i !== index);
                // updatePreview();
                selectedFiles.splice(index, 1);
                updatePreview();
                updateTargetFormat();
            }

            function updateTargetFormat() {
                if (selectedFiles.length === 0) return;

                // 统计文件格式
                const formatCounts = {};
                selectedFiles.forEach(file => {
                    const format = file.name.split('.').pop().toLowerCase();
                    formatCounts[format] = (formatCounts[format] || 0) + 1;
                });

                // 找出最常见的格式
                let mostCommonFormat = Object.entries(formatCounts)
                    .sort((a, b) => b[1] - a[1])[0][0];

                // 将最常见格式映射到目标格式选项
                const formatMapping = {
                    'jpg': 'jpeg',
                    'jpeg': 'jpeg',
                    'png': 'png',
                    'webp': 'webp',
                    'ico': 'ico'
                };

                const currentFormat = formatMapping[mostCommonFormat] || 'jpeg';

                // 设置默认选中的格式（选择一个不同的格式）
                // const formatSelect = document.getElementById('format');
                const availableFormats = Array.from(formatSelect.options)
                    .map(option => option.value)
                    .filter(format => format !== currentFormat);

                if (availableFormats.length > 0) {
                    formatSelect.value = availableFormats[0];
                }
            }

            async function convertImages() {
                if (selectedFiles.length === 0) {
                    showToast('请选择图片文件', 'error');
                    return;
                }

                // 验证自定义尺寸输入
                if (sizeSelect.value === 'custom') {
                    const width = parseInt(widthInput.value);
                    const height = parseInt(heightInput.value);
                    if (!width || !height || width <= 0 || height <= 0) {
                        showToast('请输入有效的宽度和高度', 'error');
                        return;
                    }
                }

                convertedImages.innerHTML = '';
                convertedFiles = [];
                progressContainer.classList.remove('hidden');
                downloadAllBtn.classList.add('hidden');

                const format = formatSelect.value;

                // const size = sizeInput.value;
                const sizeOption = sizeSelect.value;

                let processed = 0;

                for (const file of selectedFiles) {
                    try {
                        // const convertedFile = await convertSingleImage(file, format, size);
                        const convertedFile = await convertSingleImage(file, format, sizeOption);
                        convertedFiles.push(convertedFile);

                        processed++;
                        const progress = (processed / selectedFiles.length) * 100;
                        progressBarFill.style.width = `${progress}%`;

                        // 显示转换后的图片预览
                        const previewDiv = document.createElement('div');
                        previewDiv.className = 'preview-item';
                        previewDiv.innerHTML = `
                            <img src="${convertedFile.url}" class="output-image" alt="Converted">
                            <a href="${convertedFile.url}"
                               download="${convertedFile.filename}"
                               class="bg-green-500 hover:bg-green-600 text-white text-sm font-bold py-1 px-2 rounded mt-2 block text-center">
                                下载
                            </a>
                        `;
                        convertedImages.appendChild(previewDiv);

                    } catch (error) {
                        console.error('图片转换失败:', error);
                        showToast(`图片 ${file.name} 转换失败`, 'error');
                    }
                }

                if (convertedFiles.length > 0) {
                    downloadAllBtn.classList.remove('hidden');
                }
            }

            // async function convertSingleImage(file, format, size) {
            async function convertSingleImage(file, format, sizeOption) {
                return new Promise(async (resolve, reject) => {
                    try {
                        const img = await createImageBitmap(file);
                        const canvas = document.createElement('canvas');
                        const ctx = canvas.getContext('2d');

                        let width = img.width;
                        let height = img.height;

                        // if (size) {
                        //     const [newWidth, newHeight] = size.split('x').map(Number);
                        //     if (newWidth && newHeight) {
                        //         width = newWidth;
                        //         height = newHeight;
                        //     }
                        // }
                        // 根据选择的尺寸选项计算新的宽高
                        switch (sizeOption) {
                            case '16:9':
                                height = Math.round(width * (9/16));
                                break;
                            case '9:16':
                                width = Math.round(height * (9/16));
                                break;
                            case 'custom':
                                const customWidth = parseInt(widthInput.value);
                                const customHeight = parseInt(heightInput.value);
                                if (customWidth && customHeight) {
                                    width = customWidth;
                                    height = customHeight;
                                }
                                break;
                            case 'original':
                            default:
                                // 保持原始尺寸
                                break;
                        }

                        canvas.width = width;
                        canvas.height = height;

                        // 使用更好的图像插值算法
                        ctx.imageSmoothingEnabled = true;
                        ctx.imageSmoothingQuality = 'high';

                        ctx.drawImage(img, 0, 0, width, height);

                        let mimeType;
                        let quality = 0.92;

                        switch (format) {
                            case 'png':
                                mimeType = 'image/png';
                                break;
                            case 'jpeg':
                                mimeType = 'image/jpeg';
                                break;
                            case 'webp':
                                mimeType = 'image/webp';
                                break;
                            case 'ico':
                                mimeType = 'image/x-icon';
                                break;
                        }

                        canvas.toBlob((blob) => {
                            const url = URL.createObjectURL(blob);
                            const originalName = file.name.split('.').slice(0, -1).join('.');
                            const randomSuffix = Math.random().toString(36).substring(2, 8);
                            const filename = `${originalName}_${randomSuffix}.${format}`;

                            resolve({
                                url,
                                blob,
                                filename
                            });
                        }, mimeType, quality);

                    } catch (error) {
                        reject(error);
                    }
                });
            }

            async function downloadAllImages() {
                if (convertedFiles.length === 0) return;

                // 创建 ZIP 文件
                const zip = new JSZip();

                // 添加所有转换后的图片到 ZIP
                for (const file of convertedFiles) {
                    zip.file(file.filename, file.blob);
                }

                try {
                    // 生成 ZIP 文件
                    const content = await zip.generateAsync({type: 'blob'});

                    // 创建下载链接并触发下载
                    const downloadUrl = URL.createObjectURL(content);
                    const link = document.createElement('a');
                    link.href = downloadUrl;
                    link.download = `converted_images_${new Date().getTime()}.zip`;
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                    URL.revokeObjectURL(downloadUrl);

                    showToast('批量下载已开始', 'success');
                } catch (error) {
                    console.error('创建 ZIP 文件失败:', error);
                    showToast('创建 ZIP 文件失败', 'error');
                }
            }
        });
    </script>
@endsection

