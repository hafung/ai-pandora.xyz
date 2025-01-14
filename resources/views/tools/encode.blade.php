@extends('layouts.tools-live', [
    'title' => __('seo.encode_decode.title'),
    'description' => __('seo.encode_decode.description'),
    'keywords' => __('seo.encode_decode.keywords')
])

@section('content')
    <div class="container mx-auto px-4 py-8">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- 编码解码部分 -->
            <div class="bg-white rounded-lg shadow-lg p-6 flex flex-col">
                <h2 class="text-xl font-semibold text-gray-700 mb-4">编码 / 解码</h2>
                <div class="space-y-4 flex-grow">
                    <div>
                        <textarea id="input-encode" rows="4"
                                  class="w-full px-3 py-2 text-gray-700 border rounded-lg focus:outline-none focus:border-blue-500"
                                  placeholder="请输入要编码/解码的文本"></textarea>
                    </div>
                    <div class="flex space-x-2">
                        <button onclick="performOperation('encode', 'base64')"
                                class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                            Base64 编码
                        </button>
                        <button onclick="performOperation('decode', 'base64')"
                                class="px-4 py-2 bg-green-400 text-white rounded-md hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-opacity-50">
                            Base64 解码
                        </button>
                        <button onclick="performOperation('encode', 'url')"
                                class="px-4 py-2 bg-blue-400 text-white rounded-md hover:bg-blue-500 ">
                            URL 编码
                        </button>
                        <button onclick="performOperation('decode', 'url')"
                                class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-opacity-50">
                            URL 解码
                        </button>
                        <button onclick="performOperation('encode', 'hex')"
                                class="px-4 py-2 bg-gray-900 text-white rounded-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-opacity-50">
                            Hex 编码
                        </button>
                        <button onclick="performOperation('decode', 'hex')"
                                class="px-4 py-2 bg-green-400 text-white rounded-md hover:bg-green-500 focus:outline-none focus:ring-2 focus:ring-purple-400 focus:ring-opacity-50">
                            Hex 解码
                        </button>
                    </div>
                    <div>
                        <textarea id="output-encode" rows="4"
                                  class="w-full px-3 py-2 text-gray-700 border rounded-lg bg-gray-100 mt-5"
                                  readonly></textarea>
                    </div>
                </div>
            </div>

            <!-- 加密部分 -->
            <div class="bg-white rounded-lg shadow-lg p-6 flex flex-col">
                <h2 class="text-xl font-semibold text-gray-700 mb-4">加密 / 解密</h2>
                <div class="space-y-4 flex-grow">
                    <div>
                        <textarea id="input-encrypt" rows="4"
                                  class="w-full px-3 py-2 text-gray-700 border rounded-lg focus:outline-none focus:border-blue-500"
                                  placeholder="请输入要加密/解密的文本"></textarea>
                    </div>
                    <div class="flex space-x-2">
                        <input type="text" id="aes-passphrase"
                               class="flex-grow px-3 py-2 text-gray-700 border rounded-lg focus:outline-none focus:border-blue-500"
                               placeholder="AES Secret Passphrase">
                        <button onclick="performEncryption('md5')"
                                class="px-4 py-2 bg-red-300 text-white font-bold rounded-md hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-opacity-50 shadow-md">
                            MD5
                        </button>
                        <button onclick="performEncryption('aes', 'encrypt')"
                                class="px-4 py-2 bg-indigo-500 text-white rounded-md hover:bg-indigo-600 ">
                            AES 加密
                        </button>
                        <button onclick="performEncryption('aes', 'decrypt')"
                                class="px-4 py-2 bg-indigo-500 text-white rounded-md hover:bg-indigo-700 ">
                            AES 解密
                        </button>
                        <button onclick="performEncryption('sha256')"
                                class="px-4 py-2 bg-yellow-400 text-white font-bold rounded-md hover:bg-yellow-600 shadow-md">
                            SHA256
                        </button>
                    </div>
                    <div>
                        <textarea id="output-encrypt" rows="4"
                                  class="w-full px-3 py-2 text-gray-700 border rounded-lg bg-gray-100 mt-5"
                                  readonly></textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.1.1/crypto-js.min.js"></script>
    <script>
        function performOperation(operation, type) {
            const input = document.getElementById('input-encode').value;
            const passphrase = document.getElementById('aes-passphrase').value;
            let output = '';

            try {
                if (operation === 'encode') {
                    if (type === 'base64') {
                        output = btoa(unescape(encodeURIComponent(input)));
                    } else if (type === 'url') {
                        output = encodeURIComponent(input);
                    } else if (type === 'hex') {
                        output = Array.from(input).map(char => char.charCodeAt(0).toString(16).padStart(2, '0')).join('');
                    }
                } else if (operation === 'decode') {
                    if (type === 'base64') {
                        output = decodeURIComponent(escape(atob(input)));
                    } else if (type === 'url') {
                        output = decodeURIComponent(input);
                    } else if (type === 'hex') {
                        output = input.match(/.{1,2}/g).map(byte => String.fromCharCode(parseInt(byte, 16))).join('');
                    }
                }
                document.getElementById('output-encode').value = output;
            } catch (error) {
                // alert('操作失败：' + error.message);
                showToast('操作失败：' + error.message, 'error')
            }
        }

        function performEncryption(type) {
            const input = document.getElementById('input-encrypt').value;
            let output = '';

            try {
                switch (type) {
                    case 'md5':
                        output = CryptoJS.MD5(input).toString();
                        break;
                    case 'sha1':
                        output = CryptoJS.SHA1(input).toString();
                        break;
                    case 'sha256':
                        output = CryptoJS.SHA256(input).toString();
                        break;
                    case 'aes':
                        if (!passphrase) {
                            throw new Error('AES 加密/解密需要 Secret Passphrase');
                        }
                        if (operation === 'encrypt') {
                            output = CryptoJS.AES.encrypt(input, passphrase).toString();
                        } else if (operation === 'decrypt') {
                            const decrypted = CryptoJS.AES.decrypt(input, passphrase);
                            output = decrypted.toString(CryptoJS.enc.Utf8);
                        }
                        break;
                    default:
                        throw new Error('未知的加密类型');
                }
                document.getElementById('output-encrypt').value = output;
            } catch (error) {
                // alert('加密失败：' + error.message);
                showToast('加密失败：' + error.message, 'error')
            }
        }

        // 添加复制功能
        function copyToClipboard(elementId) {
            const element = document.getElementById(elementId);
            element.select();
            document.execCommand('copy');
            // alert('已复制到剪贴板！');
            showToast('已复制到剪贴板!')
        }

        // 为输出框添加复制按钮
        function addCopyButtons() {
            const outputAreas = ['output-encode', 'output-encrypt'];
            outputAreas.forEach(id => {
                const outputArea = document.getElementById(id);
                const copyButton = document.createElement('button');
                copyButton.textContent = '复制';
                copyButton.className = 'w-full px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600';
                copyButton.onclick = () => copyToClipboard(id);
                outputArea.parentNode.insertBefore(copyButton, outputArea.nextSibling);
            });
        }

        // 页面加载完成后添加复制按钮
        window.onload = addCopyButtons;
    </script>
@endpush
