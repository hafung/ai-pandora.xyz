@extends('layouts.tools-live', [
    'title' => __('seo.guid_generator.title'),
    'description' => __('seo.guid_generator.description'),
    'keywords' => __('seo.guid_generator.keywords')
])

@section('content')
    <div class="bg-white p-6 rounded-lg shadow-md">
        <div class="mb-4">
            <textarea id="guidOutput" readonly class="w-full p-2 border border-gray-300 rounded" rows="4"></textarea>
        </div>
        <div class="mb-4 flex space-x-2">
            <button id="generateGuid" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                {{__('Generate')}} GUID/UUID
            </button>
            <button id="copyGuid" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded">
                {{__('Copy')}}
            </button>
        </div>
        <div class="mb-4">
            <label class="block mb-2">{{__('Options')}}：</label>
            <div class="flex space-x-4">
                <label>
                    <input type="radio" name="guidVersion" value="v4" checked> UUID v4 (随机)
                </label>
                <label>
                    <input type="radio" name="guidVersion" value="v1"> UUID v1 (基于时间)
                </label>
                <label>
                    <input type="radio" name="guidVersion" value="v5"> UUID v5 (基于名称)
                </label>
            </div>
        </div>
        <div id="v5Options" class="mb-4 hidden">
            <label for="v5Namespace" class="block mb-2">{{__('Namespace')}} UUID:</label>
            <input type="text" id="v5Namespace" class="w-full p-2 border border-gray-300 rounded mb-2" placeholder="输入有效的 UUID">
            <label for="v5Name" class="block mb-2">{{__('Name')}}:</label>
            <input type="text" id="v5Name" class="w-full p-2 border border-gray-300 rounded" placeholder="输入名称">
        </div>
        <div class="mb-4">
            <label for="guidCount" class="block mb-2">{{__('Generate quantity')}}}：</label>
            <input type="number" id="guidCount" min="1" max="100" value="1" class="w-20 p-2 border border-gray-300 rounded">
        </div>
        <div class="mb-4">
            <label for="guidFormat" class="block mb-2">{{__('Format')}}：</label>
            <select id="guidFormat" class="w-full p-2 border border-gray-300 rounded">
                <option value="default">{{__('Default (hyphenated)')}}</option>
                <option value="noHyphen">{{__('Without hyphens')}}</option>
                <option value="uppercase">{{__('Uppercase')}}</option>
            </select>
        </div>
        <div class="mt-6">
            <h3 class="text-lg font-semibold mb-2">{{__('Generate history')}}：</h3>
            <ul id="guidHistory" class="list-disc pl-5"></ul>
        </div>
    </div>
    <div id="toast" class="toast">已复制到剪贴板</div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const guidOutput = document.getElementById('guidOutput');
            const generateButton = document.getElementById('generateGuid');
            const copyButton = document.getElementById('copyGuid');
            const guidCount = document.getElementById('guidCount');
            const guidFormat = document.getElementById('guidFormat');
            const guidHistory = document.getElementById('guidHistory');
            const v5Options = document.getElementById('v5Options');
            const v5Namespace = document.getElementById('v5Namespace');
            const v5Name = document.getElementById('v5Name');
            const toast = document.getElementById('toast');

            function generateUUIDv4() {
                return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
                    var r = Math.random() * 16 | 0,
                        v = c == 'x' ? r : (r & 0x3 | 0x8);
                    return v.toString(16);
                });
            }

            function generateUUIDv1() {
                let d = new Date().getTime();
                if (typeof performance !== 'undefined' && typeof performance.now === 'function'){
                    d += performance.now();
                }
                return 'xxxxxxxx-xxxx-1xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
                    var r = (d + Math.random()*16)%16 | 0;
                    d = Math.floor(d/16);
                    return (c=='x' ? r : (r&0x3|0x8)).toString(16);
                });
            }

            // SHA-1 哈希函数实现
            function sha1(str) {
                function rotl(n,s) { return n<<s|n>>>32-s; };
                function tohex(i) { for(var h="", s=28;s>=0;s-=4) h+=(i>>>s&0xf).toString(16); return h; };
                var H0=0x67452301, H1=0xEFCDAB89, H2=0x98BADCFE, H3=0x10325476, H4=0xC3D2E1F0, M=0x0ffffffff;
                var i, t, W=new Array(80), ml=str.length*8, bl=str.length*8, words=new Array();
                for(i=0;i<bl;i+=8) words[i>>5]|=(str.charCodeAt(i/8)&0xff)<<(24-i%32);
                words[ml>>5]|=0x80<<(24-ml%32);
                words[(((ml+64)>>>9)<<4)+15]=ml;
                for(i=0;i<words.length;i+=16) {
                    var a=H0, b=H1, c=H2, d=H3, e=H4;
                    for(t=0;t<80;t++) {
                        if(t<16) W[t]=words[i+t];
                        else W[t]=rotl(W[t-3]^W[t-8]^W[t-14]^W[t-16],1);
                        var s=rotl(a,5)+((t<20)?((b&c)|((~b)&d))+0x5A827999:(t<40)?(b^c^d)+0x6ED9EBA1:(t<60)?((b&c)|(b&d)|(c&d))+0x8F1BBCDC:(b^c^d)+0xCA62C1D6)+e+W[t];
                        e=d; d=c; c=rotl(b,30); b=a; a=s;
                    }
                    H0=H0+a&M; H1=H1+b&M; H2=H2+c&M; H3=H3+d&M; H4=H4+e&M;
                }
                return tohex(H0)+tohex(H1)+tohex(H2)+tohex(H3)+tohex(H4);
            }

            function generateUUIDv5(namespace, name) {
                if (!namespace.match(/^[0-9a-f]{8}-[0-9a-f]{4}-[1-5][0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i)) {
                    throw new Error('Invalid namespace UUID');
                }

                // 移除命名空间 UUID 中的连字符
                const cleanNamespace = namespace.replace(/-/g, '');

                // 将命名空间 UUID 转换为字节数组
                const namespaceBytes = [];
                for (let i = 0; i < cleanNamespace.length; i += 2) {
                    namespaceBytes.push(parseInt(cleanNamespace.substr(i, 2), 16));
                }

                // 将名称转换为 UTF-8 字节数组
                const nameBytes = new TextEncoder().encode(name);

                // 连接命名空间和名称字节
                const bytes = [...namespaceBytes, ...nameBytes];

                // 计算 SHA-1 哈希
                const hash = sha1(String.fromCharCode.apply(null, bytes));

                // 构造 UUID
                let uuid = hash.substr(0, 8) + '-' +
                    hash.substr(8, 4) + '-' +
                    '5' + hash.substr(13, 3) + '-' +
                    ((parseInt(hash.substr(16, 2), 16) & 0x3f) | 0x80).toString(16) + hash.substr(18, 2) + '-' +
                    hash.substr(20, 12);

                return uuid;
            }

            function formatGuid(guid, format) {
                switch (format) {
                    case 'noHyphen':
                        return guid.replace(/-/g, '');
                    case 'uppercase':
                        return guid.toUpperCase();
                    default:
                        return guid;
                }
            }

            function generateGuid() {
                const count = parseInt(guidCount.value);
                const format = guidFormat.value;
                const version = document.querySelector('input[name="guidVersion"]:checked').value;
                let guids = [];

                for (let i = 0; i < count; i++) {
                    let guid;
                    if (version === 'v4') {
                        guid = generateUUIDv4();
                    } else if (version === 'v1') {
                        guid = generateUUIDv1();
                    } else if (version === 'v5') {
                        const namespace = v5Namespace.value;
                        const name = v5Name.value;
                        if (!namespace || !name) {
                            toast.innerText = '请为 UUID v5 提供有效的命名空间和名称';
                            toast.classList.add('show');
                            setTimeout(() => {
                                toast.classList.remove('show');
                            }, 2200);
                            return;
                        }
                        guid = generateUUIDv5(namespace, name);
                    }
                    guids.push(formatGuid(guid, format));
                }

                guidOutput.value = guids.join('\n');
                addToHistory(guids[0]);
            }

            function addToHistory(guid) {
                const li = document.createElement('li');
                li.textContent = guid;
                guidHistory.prepend(li);

                if (guidHistory.children.length > 5) {
                    guidHistory.removeChild(guidHistory.lastChild);
                }
            }

            function copyToClipboard() {
                guidOutput.select();
                document.execCommand('copy');

                toast.classList.add('show');
                setTimeout(() => {
                    toast.classList.remove('show');
                }, 2200);
            }

            generateButton.addEventListener('click', generateGuid);
            copyButton.addEventListener('click', copyToClipboard);

            document.querySelectorAll('input[name="guidVersion"]').forEach(radio => {
                radio.addEventListener('change', function() {
                    v5Options.style.display = this.value === 'v5' ? 'block' : 'none';
                });
            });
        });
    </script>
@endpush


@push('styles')
    <style>
        .toast {
            position: fixed;
            top: 60px;  /* 距离顶部的距离，可以根据需要调整 */
            left: 50%;
            transform: translateX(-50%);
            background-color: rgba(0, 0, 0, 0.7);
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 14px;
            opacity: 0;
            transition: opacity 0.3s ease-in-out, top 0.3s ease-in-out;
            z-index: 1000;
        }

        .toast.show {
            opacity: 1;
            top: 70px;  /* 显示时略微下移，创造一个小动画效果 */
        }
    </style>
@endpush
