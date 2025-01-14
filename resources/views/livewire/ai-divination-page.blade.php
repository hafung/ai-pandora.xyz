<div class="min-h-screen bg-gray-900 text-gray-100 p-6">
    <div id="three-container" class="absolute inset-0 z-0"></div>
    <div class="relative z-10 max-w-3xl mx-auto">
        <h1 class="text-4xl font-bold mb-8 text-center text-purple-400">🔮✨神秘占卜🌙🃏</h1>

        <div class="mb-6">
            <textarea wire:model.defer="prompt"
                      wire:keydown.enter="generate"
                      class="w-full p-4 bg-gray-800 border border-purple-500 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600"
                      rows="4"
                      placeholder="{{__('Tell me the first sentence that comes to your mind right now')}}..."></textarea>
        </div>

        <div class="text-center">
            <button wire:click="generate"
                    class="px-6 py-3 bg-purple-600 hover:bg-purple-700 rounded-lg transition duration-300 ease-in-out transform hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed"
                    wire:loading.attr="disabled">
                <span wire:loading.remove>开始占卜</span>
                <span wire:loading>正在占卜...</span>
            </button>
        </div>

{{--        <div wire:loading wire:target="generate" class="mt-8">--}}
{{--            <div class="loader"></div>--}}
{{--        </div>--}}
{{--        <div wire:loading wire:target="generate" class="mt-8">--}}
{{--            <div class="crystal-ball"></div>--}}
{{--        </div>--}}
{{--        <div wire:loading wire:target="generate" class="mt-8 flex justify-center">--}}
{{--            <div class="crystal-ball-container">--}}
{{--                <div class="crystal-ball"></div>--}}
{{--                <div class="crystal-base"></div>--}}
{{--                <div class="magic-sparkles"></div>--}}
{{--            </div>--}}
{{--        </div>--}}
        <div wire:loading wire:target="generate" class="loading-container">
            <div class="crystal-ball-container">
                <div class="crystal-ball"></div>
{{--                <div class="crystal-base"></div>--}}
                <div class="magic-sparkles"></div>
            </div>
        </div>

        @if($generatedContent)
            <div class="mt-8 p-6 bg-gray-800 rounded-lg border border-purple-500 shadow-lg">
                <h2 class="text-2xl font-semibold mb-4 text-purple-400">占卜结果</h2>
                <p class="text-gray-300">{{ $generatedContent }}</p>
            </div>
        @endif
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
    <script>

        let threeJsInitialized = false;

        function initThreeJs() {
            if (threeJsInitialized) return;

            const container = document.getElementById('three-container');
            const scene = new THREE.Scene();
            const camera = new THREE.PerspectiveCamera(75, container.clientWidth / container.clientHeight, 0.1, 1000);
            const renderer = new THREE.WebGLRenderer({alpha: true});
            renderer.setSize(container.clientWidth, container.clientHeight);
            container.appendChild(renderer.domElement);

            // 创建星星
            const starsGeometry = new THREE.BufferGeometry();
            const starsMaterial = new THREE.PointsMaterial({color: 0xFFFFFF, size: 0.1});

            const starsVertices = [];
            for (let i = 0; i < 10000; i++) {
                const x = (Math.random() - 0.5) * 2000;
                const y = (Math.random() - 0.5) * 2000;
                const z = -Math.random() * 2000;
                starsVertices.push(x, y, z);
            }

            starsGeometry.setAttribute('position', new THREE.Float32BufferAttribute(starsVertices, 3));
            const starField = new THREE.Points(starsGeometry, starsMaterial);
            scene.add(starField);

            camera.position.z = 5;

            function animate() {
                requestAnimationFrame(animate);
                starField.rotation.y += 0.0002;
                renderer.render(scene, camera);
            }

            animate();

            // 调整窗口大小时重新设置渲染器和相机
            window.addEventListener('resize', function () {
                camera.aspect = container.clientWidth / container.clientHeight;
                camera.updateProjectionMatrix();
                renderer.setSize(container.clientWidth, container.clientHeight);
            });

            threeJsInitialized = true;
        }

        document.addEventListener('livewire:load', initThreeJs);
        document.addEventListener('livewire:update', initThreeJs);
        window.addEventListener('error', function (event) {
            console.error('JavaScript error:', event.error);
        });

    </script>
</div>

@push('styles')
    <style>
        /*.loader {*/
        /*    border: 5px solid #4B5563;*/
        /*    border-top: 5px solid #8B5CF6;*/
        /*    border-radius: 50%;*/
        /*    width: 50px;*/
        /*    height: 50px;*/
        /*    animation: spin 1s linear infinite;*/
        /*    margin: 0 auto;*/
        /*}*/

        /*@keyframes spin {*/
        /*    0% {*/
        /*        transform: rotate(0deg);*/
        /*    }*/
        /*    100% {*/
        /*        transform: rotate(360deg);*/
        /*    }*/
        /*}*/
        .loading-container {
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            top: 100%;
            margin-top: 2rem;
            width: 100%;
        }

        .crystal-ball-container {
            position: relative;
            width: 120px;
            height: 160px;
            margin: 0 auto;
        }

        .crystal-ball {
            width: 100px;
            height: 100px;
            background: radial-gradient(circle at 50% 40%, #fcfcfc, #efeff1 66%, #9b5de5 100%);
            border-radius: 50%;
            box-shadow: 0 0 20px rgba(155, 93, 229, 0.6),
            inset 0 0 20px rgba(255, 255, 255, 0.8);
            position: absolute;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            animation: glow 2s ease-in-out infinite alternate,
            levitate 3s ease-in-out infinite;
        }

        .crystal-ball::before {
            content: '';
            position: absolute;
            top: 5%;
            left: 10%;
            width: 30%;
            height: 30%;
            background: rgba(255, 255, 255, 0.6);
            border-radius: 50%;
            transform: rotate(40deg);
        }

        .crystal-ball::after {
            content: '✨';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 24px;
            color: #9b5de5;
            animation: twinkle 1s ease-in-out infinite;
        }

        .crystal-base {
            width: 80px;
            height: 40px;
            background: linear-gradient(to right, #8e2de2, #4a00e0);
            border-radius: 50% / 100% 100% 0 0;
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            box-shadow: 0 10px 20px rgba(74, 0, 224, 0.4);
        }

        .magic-sparkles {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            pointer-events: none;
        }

        .magic-sparkles::before,
        .magic-sparkles::after {
            content: '';
            position: absolute;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background-color: #fff;
            opacity: 0;
            animation: sparkle 1.5s ease-in-out infinite;
        }

        .magic-sparkles::before {
            top: 20%;
            left: 20%;
            animation-delay: 0.5s;
        }

        .magic-sparkles::after {
            top: 80%;
            right: 20%;
            animation-delay: 1s;
        }

        @keyframes glow {
            0% { box-shadow: 0 0 20px rgba(155, 93, 229, 0.6),
            inset 0 0 20px rgba(255, 255, 255, 0.8); }
            100% { box-shadow: 0 0 40px rgba(155, 93, 229, 0.8),
            inset 0 0 30px rgba(255, 255, 255, 0.9); }
        }

        @keyframes levitate {
            0%, 100% { transform: translateX(-50%) translateY(0); }
            50% { transform: translateX(-50%) translateY(-10px); }
        }

        @keyframes twinkle {
            0%, 100% { opacity: 1; transform: translate(-50%, -50%) scale(1); }
            50% { opacity: 0.5; transform: translate(-50%, -50%) scale(0.8); }
        }

        @keyframes sparkle {
            0%, 100% { opacity: 0; transform: scale(0); }
            50% { opacity: 1; transform: scale(1); }
        }

        /* 添加流光特效 */
        .bg-gray-900::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, #ff00ff, #00ffff, #ff00ff);
            opacity: 0.05;
            filter: blur(100px);
            z-index: -1;
            animation: flow 15s ease infinite;
        }

        @keyframes flow {
            0% {
                background-position: 0 50%;
            }
            50% {
                background-position: 100% 50%;
            }
            100% {
                background-position: 0 50%;
            }
        }
    </style>
@endpush
