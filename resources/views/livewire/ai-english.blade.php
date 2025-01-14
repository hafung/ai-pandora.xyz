<div class="flex flex-col items-center justify-start min-h-screen pt-8 px-4 bg-gray-100">
    <div wire:offline.class="bg-red-300"></div>
    <!-- Logo -->
    <div class="mb-1 mt-1" id="logoContainer">
        <svg width="160" height="160" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <linearGradient id="bgGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                    <stop offset="0%" style="stop-color:#4A00E0;stop-opacity:1"/>
                    <stop offset="100%" style="stop-color:#00E5FF;stop-opacity:1"/>
                </linearGradient>
                <filter id="glow">
                    <feGaussianBlur stdDeviation="2.5" result="coloredBlur"/>
                    <feMerge>
                        <feMergeNode in="coloredBlur"/>
                        <feMergeNode in="SourceGraphic"/>
                    </feMerge>
                </filter>
            </defs>

            <!-- 背景 -->
            <rect x="0" y="0" width="200" height="200" fill="url(#bgGradient)" rx="30" ry="30"/>

            <!-- 抽象AI网络 -->
            <g stroke="#FFFFFF" stroke-width="0.5" opacity="0.6">
                <line x1="40" y1="40" x2="160" y2="160"/>
                <line x1="160" y1="40" x2="40" y2="160"/>
                <line x1="100" y1="20" x2="100" y2="180"/>
                <line x1="20" y1="100" x2="180" y2="100"/>
            </g>

            <!-- 中心球体 -->
            <circle cx="100" cy="100" r="50" fill="#FFFFFF" opacity="0.1"/>
            <circle cx="100" cy="100" r="40" fill="#FFFFFF" opacity="0.2"/>

            <!-- AI文字 -->
            <text x="100" y="110" font-family="Arial, sans-serif" font-size="60" font-weight="bold" fill="#FFFFFF"
                  text-anchor="middle" filter="url(#glow)">AI
            </text>

            <!-- 动态波浪（代表语言） -->
            <path d="M20,140 Q50,120 80,140 T140,140 T200,140" fill="none" stroke="#FFD700" stroke-width="3">
                <animate attributeName="d"
                         dur="5s"
                         repeatCount="indefinite"
                         values="M20,140 Q50,120 80,140 T140,140 T200,140;
                         M20,140 Q50,160 80,140 T140,140 T200,140;
                         M20,140 Q50,120 80,140 T140,140 T200,140"/>
            </path>

            <text x="100" y="180" font-family="Arial, sans-serif" font-size="16" font-weight="bold" fill="#FFFFFF"
                  text-anchor="middle">ENGLISH LEARNING
            </text>
        </svg>
    </div>

    <div class="flex space-x-4 p-4 rounded-lg">
        <div class="relative">
            <input type="radio" wire:model.lazy="mode" wire:loading.attr="disabled" value="search" id="search-mode"
                   class="peer hidden">
            <label for="search-mode"
                   class="flex items-center px-4 py-2 text-sm font-medium text-gray-600 bg-white border-2 border-gray-200 rounded-md cursor-pointer transition-all duration-200 ease-in-out hover:bg-indigo-50 hover:border-indigo-300 peer-checked:bg-indigo-100 peer-checked:border-indigo-500 peer-checked:text-indigo-700 group">

                <svg xmlns="http://www.w3.org/2000/svg"
                     class="h-5 w-5 mr-2 text-gray-400 group-hover:text-indigo-500 peer-checked:text-indigo-600 transition-colors duration-200"
                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <span class="relative">
                查询模式
                <span
                    class="absolute bottom-0 left-0 w-full h-0.5 bg-indigo-500 transform scale-x-0 transition-transform duration-200 group-hover:scale-x-100 peer-checked:scale-x-100"></span>
            </span>
            </label>
        </div>

        <div class="relative">
            <input type="radio" wire:model.lazy="mode" wire:loading.attr="disabled" value="progress" id="progress-mode"
                   class="peer hidden">
            <label for="progress-mode"
                   class="flex items-center px-4 py-2 text-sm font-medium text-gray-600 bg-white border-2 border-gray-200 rounded-md cursor-pointer transition-all duration-200 ease-in-out hover:bg-green-50 hover:border-green-300 peer-checked:bg-green-100 peer-checked:border-green-500 peer-checked:text-green-700 group">
                <svg xmlns="http://www.w3.org/2000/svg"
                     class="h-5 w-5 mr-2 text-gray-400 group-hover:text-green-500 peer-checked:text-green-600 transition-colors duration-200"
                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                </svg>
                <span class="relative">
                辞海模式
                <span
                    class="absolute bottom-0 left-0 w-full h-0.5 bg-green-500 transform scale-x-0 transition-transform duration-200 group-hover:scale-x-100 peer-checked:scale-x-100"></span>
            </span>
            </label>
        </div>
        <input type="hidden" wire:model="uniqueId" id="uniqueIdInput">
    </div>

    <!-- Content Area -->
    <div class="w-full max-w-2xl mx-auto mt-2">
        @if ($mode === 'search')
            <div class="relative">

                <input type="text"
                       wire:model.debounce.100ms="searchQuery"
                       wire:keydown.enter="search"
                       class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-300"
                       placeholder="输入搜索内容...">

                <button wire:click="search"
                        wire:loading.attr="disabled"
                        wire:loading.class="opacity-50 cursor-not-allowed"
                        wire:target="search"
                        class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700 focus:outline-none transition duration-300 ease-in-out"
                        :disabled="$wire.searchQuery === ''"
                >
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                         xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <div wire:loading wire:target="search" class="absolute inset-0 flex items-center justify-center">
                        <svg class="animate-spin h-5 w-5 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                             viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                  d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                </button>
            </div>
            @error('errorMessage')
            <div
                class="mt-2 flex items-center space-x-2 text-red-600 bg-red-100 border border-red-400 rounded-md px-3 py-2 transition-all duration-300 ease-in-out opacity-100">
                <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                          d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                          clip-rule="evenodd"/>
                </svg>
                <span class="text-sm font-medium">{{ $message }}</span>
            </div>
            @enderror
        @else

            <button wire:click="progress"
                    wire:loading.class="bg-indigo-600 hover:bg-indigo-700"
                    wire:loading.attr="disabled"
                    class="w-full bg-gradient-to-r from-cyan-500 to-blue-500 hover:from-cyan-600 hover:to-blue-600 text-white font-bold py-4 px-6 rounded-lg transition duration-300 ease-in-out text-xl focus:outline-none focus:ring-2 focus:ring-blue-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1 relative overflow-hidden group"
            >
                <span
                    class="absolute inset-0 w-full h-full bg-gradient-to-r from-transparent via-white to-transparent opacity-25 transform -skew-x-12 -translate-x-full group-hover:translate-x-full transition-transform duration-1000 ease-in-out"></span>
                <span class="relative inline-flex items-center justify-center w-full">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24"
             stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
        </svg>
        <span wire:loading.remove wire:target="progress">
            开启辞海
        </span>
        <span wire:loading wire:target="progress" class="flex items-center">
            量子跃迁中
            <span class="flex ml-2">
                <span class="pulse h-2 w-2 bg-white rounded-full"></span>
                <span class="pulse h-2 w-2 bg-white rounded-full mx-1" style="animation-delay: 0.2s;"></span>
                <span class="pulse h-2 w-2 bg-white rounded-full" style="animation-delay: 0.4s;"></span>
            </span>
        </span>
    </span>
            </button>

        @endif
    </div>

    <div
        class="w-[100%] max-w-[100%] mt-1 mx-auto rounded-lg overflow-hidden transition-all duration-300 ease-in-out transform hover:scale-105 relative group">
        <button id="playAudioBtn"
                wire:loading.remove
                wire:target="progress,search"
                class="absolute top-4 right-2 bg-green-400 hover:bg-green-500 text-white rounded-full p-2 transition duration-300 ease-in-out focus:outline-none focus:ring-2 focus:ring-green-300 opacity-0 group-hover:opacity-100"
                style="display: {{ !empty($svgCards[$mode]) ? 'block' : 'none' }};">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                 stroke="currentColor"
            >
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </button>

        <div wire:loading.block wire:target="progress,search">
            <!-- 加载动画 -->
            <div class="spinner">
                <div class="rect1"></div>
                <div class="rect2"></div>
                <div class="rect3"></div>
                <div class="rect4"></div>
                <div class="rect5"></div>
            </div>

        </div>
        <div class="w-full flex justify-center" id="svgCardContainer" style="display: none;">

            <div wire:loading.remove wire:target="progress,search">
                @if(!empty($svgCards[$mode]))
                    {!! $svgCards[$mode] !!}
                @endif
            </div>
        </div>

    </div>

    <script>

        document.addEventListener('DOMContentLoaded', function() {
            const avatarElement = document.getElementById('avatar');
            if (avatarElement) {
                const avatarSvg = getOrCreateAvatar();
                avatarElement.style.backgroundImage = `url('${avatarSvg}')`;
                avatarElement.style.backgroundSize = 'cover';
                avatarElement.style.backgroundPosition = 'center';
            }
        });

        document.addEventListener('livewire:load', function () {
            let isPlaying = false;

            const uniqueId = getOrCreateUniqueId();

            Livewire.emit('setUniqueId', uniqueId);

            // const downloadBtn = document.getElementById('downloadBtn');
            const svgCardContainer = document.getElementById('svgCardContainer');
            const playAudioBtn = document.getElementById('playAudioBtn');

            const logoContainer = document.getElementById('logoContainer');

            let queryCount = localStorage.getItem('queryCount_' + uniqueId) || 0;
            let progressIndex = localStorage.getItem('progressIndex_' + uniqueId) || 0;

            document.getElementById('query-count').textContent = queryCount;
            document.getElementById('progress-index').textContent = progressIndex;

            // 当SVG加载完成时显示下载按钮
            Livewire.on('svgGenerated', function (data) {

                if (logoContainer && svgCardContainer) {

                    logoContainer.classList.add('hide'); // 添加 hide 类来触发过渡效果

                    // 等待 logo 消失动画完成后再显示 SVG 卡片
                    setTimeout(() => {
                        svgCardContainer.style.display = 'flex';
                        // 使用 setTimeout 来确保浏览器有时间应用新的 display 值
                        setTimeout(() => {
                            svgCardContainer.style.opacity = '1';
                        }, 50);
                    }, 500); // 这个延迟应该与 CSS 过渡时间相匹配
                }


                if (data.mode === 'search') {
                    queryCount++;
                } else {
                    progressIndex++;
                }

                localStorage.setItem('queryCount_' + uniqueId, queryCount);
                localStorage.setItem('progressIndex_' + uniqueId, progressIndex);

                document.getElementById('query-count').textContent = queryCount;
                document.getElementById('progress-index').textContent = progressIndex;

            });

            function updateButtonState(state) {
                switch (state) {
                    case 'playing':
                        playAudioBtn.classList.add('bg-yellow-400', 'hover:bg-yellow-500');
                        playAudioBtn.classList.remove('bg-green-400', 'hover:bg-green-500');
                        playAudioBtn.innerHTML = `
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 pulse" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5" />
                                <path class="wave1" d="M15.54 8.46a5 5 0 0 1 0 7.07">
                                    <animate attributeName="opacity" values="0.3;1;0.3" dur="1s" repeatCount="indefinite" />
                                </path>
                                <path class="wave2" d="M19.07 4.93a10 10 0 0 1 0 14.14">
                                    <animate attributeName="opacity" values="0.3;1;0.3" dur="1.5s" repeatCount="indefinite" />
                                </path>
                            </svg>
                        `;
                        break;
                    case 'finished':
                        playAudioBtn.classList.add('bg-blue-400', 'hover:bg-blue-500');
                        playAudioBtn.classList.remove('bg-yellow-400', 'hover:bg-yellow-500');
                        playAudioBtn.innerHTML = `
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        `;
                        setTimeout(() => {
                            playAudioBtn.classList.add('bg-green-400', 'hover:bg-green-500');
                            playAudioBtn.classList.remove('bg-blue-400', 'hover:bg-blue-500');
                            playAudioBtn.innerHTML = `
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        `;
                        }, 2000);
                        break;
                    case 'error':
                        playAudioBtn.classList.add('bg-red-400', 'hover:bg-red-500');
                        playAudioBtn.classList.remove('bg-yellow-400', 'hover:bg-yellow-500');
                        playAudioBtn.innerHTML = `
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        `;
                        setTimeout(() => {
                            playAudioBtn.classList.add('bg-green-400', 'hover:bg-green-500');
                            playAudioBtn.classList.remove('bg-red-400', 'hover:bg-red-500');
                            playAudioBtn.innerHTML = `
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        `;
                        }, 2000);
                        break;
                }
            }

            playAudioBtn.addEventListener('click', function () {

                if (isPlaying) return;

                isPlaying = true;

                updateButtonState('playing');

                let txtAll = svgCardContainer.innerText || svgCardContainer.textContent;

                txtAll = txtAll.replace(/\/[^/]+\//g, '')
                    .replace(/([\u2700-\u27BF]|[\uE000-\uF8FF]|\uD83C[\uDC00-\uDFFF]|\uD83D[\uDC00-\uDFFF]|[\u2011-\u26FF]|\uD83E[\uDD10-\uDDFF])/g, '')
                    .replace(/[^\p{L}\p{N}\p{P}\p{Z}\n\r]/gu, '');

                // console.log(txtAll)

                const utterance = new SpeechSynthesisUtterance(`${txtAll}`);
                const voices = speechSynthesis.getVoices();
                utterance.rate = 0.8;  // 稍微放慢速度
                utterance.pitch = 1.1; // 稍微提高音调

                // 选择一个更自然的声音，通常带有 "neural" 或 "premium" 的声音效果更好
                const betterVoice = voices.find(voice =>
                    voice.name.includes('neural') || voice.name.includes('premium') || voice.name.includes('enhanced')
                );

                if (betterVoice) {
                    utterance.voice = betterVoice;
                }
                utterance.lang = 'en-US';

                // 当语音开始时触发
                utterance.onstart = function (e) {
                    console.log('Started speaking');
                };

                // 当语音结束时触发
                utterance.onend = function (e) {
                    console.log('Finished speaking');
                    isPlaying = false;
                    updateButtonState('finished');
                };

                // 如果发生错误
                utterance.onerror = function (e) {
                    console.error('Speech error:', e);
                    isPlaying = false;
                    updateButtonState('error');
                };

                // 使用浏览器的语音合成API朗读文本
                window.speechSynthesis.speak(utterance);
            })
        });

    </script>
</div>

@push('styles')
    <style>
        #logoContainer {
            transition: all 0.5s ease-in-out;
            opacity: 1;
            max-height: 160px; /* 根据你的 logo 实际高度调整 */
            overflow: hidden;
        }

        #logoContainer.hide {
            opacity: 0;
            max-height: 0;
            margin: 0;
        }
        #svgCardContainer {
            transition: all 0.5s ease-in-out;
        }
        .spinner {
            margin: 100px auto;
            width: 50px;
            height: 40px;
            text-align: center;
            font-size: 10px;
        }

        .spinner > div {
            background-color: #333;
            height: 100%;
            width: 6px;
            display: inline-block;

            -webkit-animation: sk-stretchdelay 1.2s infinite ease-in-out;
            animation: sk-stretchdelay 1.2s infinite ease-in-out;
        }

        .spinner .rect2 {
            -webkit-animation-delay: -1.1s;
            animation-delay: -1.1s;
        }

        .spinner .rect3 {
            -webkit-animation-delay: -1.0s;
            animation-delay: -1.0s;
        }

        .spinner .rect4 {
            -webkit-animation-delay: -0.9s;
            animation-delay: -0.9s;
        }

        .spinner .rect5 {
            -webkit-animation-delay: -0.8s;
            animation-delay: -0.8s;
        }

        @-webkit-keyframes sk-stretchdelay {
            0%, 40%, 100% { -webkit-transform: scaleY(0.4) }
            20% { -webkit-transform: scaleY(1.0) }
        }

        @keyframes sk-stretchdelay {
            0%, 40%, 100% {
                transform: scaleY(0.4);
                -webkit-transform: scaleY(0.4);
            }  20% {
                   transform: scaleY(1.0);
                   -webkit-transform: scaleY(1.0);
               }
        }
        @keyframes pulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: .5;
            }
        }

        .pulse {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
    </style>
@endpush
