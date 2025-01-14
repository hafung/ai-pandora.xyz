@extends('layouts.tools-live', [
    'title' => __('seo.qrcode.title'),
    'description' => __('seo.qrcode.description'),
    'keywords' => __('seo.qrcode.keywords')
])

@section('content')
    <div class="bg-gray-100 py-6 flex flex-col justify-center sm:py-12">
        <div class="relative py-3 sm:mx-auto w-full max-w-4xl">
            <div
                class="absolute inset-0 bg-gradient-to-r from-cyan-400 to-light-blue-500 shadow-lg transform -skew-y-6 sm:skew-y-0 sm:-rotate-6 sm:rounded-3xl"></div>
            <div class="relative px-4 py-10 bg-white shadow-lg sm:rounded-3xl sm:p-20">
                <div class="mx-auto">
                    <div>
                        <h1 class="text-3xl font-semibold text-gray-900 mb-6">QR Code Generator</h1>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-6">
                            <div class="flex flex-col">
                                <label for="input" class="text-lg font-medium text-gray-700 mb-2">Enter URL or
                                    Text</label>
                                <input type="text" id="input" name="input"
                                       class="px-4 py-2 border focus:ring-gray-500 focus:border-gray-900 w-full sm:text-sm border-gray-300 rounded-md focus:outline-none text-gray-600"
                                       placeholder="https://example.com">
                            </div>
                            <div class="flex flex-col">
                                <label for="style" class="text-lg font-medium text-gray-700 mb-2">Choose Border
                                    Style</label>
                                <select id="style" name="style"
                                        class="px-4 py-2 border focus:ring-gray-500 focus:border-gray-900 w-full sm:text-sm border-gray-300 rounded-md focus:outline-none text-gray-600">
                                    <option value="none">None</option>
                                    <option value="square">Square</option>
                                    <option value="rounded">Rounded</option>
                                    <option value="neon">Neon Glow</option>
                                    <option value="hologram">Hologram</option>
                                    <option value="circuit">Circuit Board</option>
                                    <option value="cyberpunk">Cyberpunk</option>
                                    <option value="matrix">Matrix Rain</option>
                                </select>
                            </div>
                            <div class="pt-4">
                                <button id="generate"
                                        class="bg-blue-500 flex justify-center items-center w-full text-white px-4 py-3 rounded-md focus:outline-none hover:bg-blue-600 transition duration-300 ease-in-out">
                                    Generate QR Code
                                </button>
                            </div>
                        </div>
                        <div id="qrcode" class="flex justify-center items-center bg-gray-50 rounded-lg"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/qrcode-generator@1.4.4/qrcode.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const input = document.getElementById('input');
            const style = document.getElementById('style');
            const generateBtn = document.getElementById('generate');
            const qrcodeDiv = document.getElementById('qrcode');

            generateBtn.addEventListener('click', function () {
                const qr = qrcode(0, 'L');
                qr.addData(input.value);
                qr.make();

                let qrImage = qr.createSvgTag(8, 0);

                // Apply border styles
                switch (style.value) {
                    case 'square':
                        qrImage = `<div class="p-4 bg-white border-2 border-gray-300">${qrImage}</div>`;
                        break;
                    case 'rounded':
                        qrImage = `<div class="p-4 bg-white border-2 border-gray-300 rounded-xl">${qrImage}</div>`;
                        break;
                    case 'neon':
                        qrImage = `<div class="p-4 bg-black border-4 border-blue-500 rounded-lg shadow-lg" style="box-shadow: 0 0 15px #3b82f6, 0 0 30px #3b82f6, 0 0 45px #3b82f6;">${qrImage}</div>`;
                        break;
                    case 'hologram':
                        qrImage = `
                            <div class="relative p-4 bg-gradient-to-r from-blue-400 to-purple-500 rounded-lg overflow-hidden" style="animation: hologram 5s linear infinite;">
                                <div class="absolute inset-0 bg-gradient-to-r from-transparent to-white opacity-25" style="animation: hologram-shine 2s linear infinite;"></div>
                                ${qrImage}
                            </div>
                        `;
                        break;
                    case 'circuit':
                        qrImage = `
                            <div class="p-4 bg-gray-900 border-2 border-green-500 rounded-lg" style="background-image: radial-gradient(circle, #4ade80 1px, transparent 1px); background-size: 10px 10px;">
                                ${qrImage}
                            </div>
                        `;
                        break;
                    case 'cyberpunk':
                        qrImage = `
                            <div class="relative p-4 bg-black border-4 border-yellow-400 rounded-lg overflow-hidden">
                                <div class="absolute inset-0 bg-gradient-to-br from-pink-500 via-purple-500 to-blue-500 opacity-50"></div>
                                <div class="relative z-10">${qrImage}</div>
                            </div>
                        `;
                        break;
                    case 'matrix':
                        qrImage = `
                            <div class="relative p-4 bg-black border-2 border-green-500 rounded-lg overflow-hidden">
                                <div class="absolute inset-0 matrix-rain"></div>
                                <div class="relative z-10">${qrImage}</div>
                            </div>
                        `;
                        break;
                }

                qrcodeDiv.innerHTML = qrImage;

                // Add Matrix Rain effect
                if (style.value === 'matrix') {
                    const rain = document.querySelector('.matrix-rain');
                    for (let i = 0; i < 50; i++) {
                        const drop = document.createElement('div');
                        drop.className = 'matrix-drop';
                        drop.style.left = `${Math.random() * 100}%`;
                        drop.style.animationDuration = `${Math.random() * 1 + 0.5}s`;
                        drop.style.animationDelay = `${Math.random() * 2}s`;
                        rain.appendChild(drop);
                    }
                }

                // Add animation for hologram effect
                if (style.value === 'hologram') {
                    const style = document.createElement('style');
                    style.textContent = `
                        @keyframes hologram {
                            0% { background-position: 0% 50%; }
                            50% { background-position: 100% 50%; }
                            100% { background-position: 0% 50%; }
                        }
                        @keyframes hologram-shine {
                            0% { transform: translateX(-100%); }
                            100% { transform: translateX(100%); }
                        }
                    `;
                    document.head.appendChild(style);
                }

            });
        });
    </script>
@endpush

@push('styles')
    <style>
        .matrix-rain {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            overflow: hidden;
        }

        .matrix-drop {
            position: absolute;
            top: -20px;
            color: #0f0;
            font-size: 14px;
            line-height: 1;
            animation: matrix-rain 1s linear infinite;
        }

        .matrix-drop::after {
            content: '0';
        }

        @keyframes matrix-rain {
            to {
                transform: translateY(200px);
                opacity: 0;
            }
        }
    </style>
@endpush
