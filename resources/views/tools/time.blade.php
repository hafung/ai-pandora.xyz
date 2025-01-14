@extends('layouts.tools-live', [
    'title' => __('seo.time_tool.title'),
    'description' => __('seo.time_tool.description'),
    'keywords' => __('seo.time_tool.keywords')
])

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h2 class="text-xl font-semibold mb-4 text-gray-700">Current Time</h2>
                <div class="space-y-2">
                    <div class="flex items-center space-x-2">
                        <input type="text" id="current-timestamp-10" readonly class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="10位时间戳">
                        <button id="copy-timestamp-10" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            复制
                        </button>
                    </div>
                    <div class="flex items-center space-x-2">
                        <input type="text" id="current-timestamp-13" readonly class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="13位时间戳">
                        <button id="copy-timestamp-13" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            复制
                        </button>
                    </div>
                    <div class="flex items-center space-x-2">
                        <input type="text" id="current-local-time" readonly class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="当前时区时间">
                        <button id="copy-local-time" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            复制
                        </button>
                    </div>
                </div>
            </div>

            <!-- Timestamp Converter Section -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h2 class="text-xl font-semibold mb-4 text-gray-700">Time Converter</h2>
                <div class="space-y-4">
                    <div>
                        <input type="text" id="inputTimestamp" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="输入时间戳或日期字符串">
                    </div>
                    <div>
                        <select id="outputFormat" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <option value="ISO8601">ISO 8601 (YYYY-MM-DDTHH:mm:ss.sssZ)</option>
                            <option value="US">US (MM/DD/YYYY hh:mm:ss A)</option>
                            <option value="UK">UK (DD/MM/YYYY HH:mm:ss)</option>
                            <option value="EU">EU (DD.MM.YYYY HH:mm:ss)</option>
                            <option value="RFC2822">RFC 2822 (ddd, DD MMM YYYY HH:mm:ss ZZ)</option>
                            <option value="YYYY-MM-DD HH:mm:ss">YYYY-MM-DD HH:mm:ss</option>
                            <option value="MM/DD/YYYY HH:mm:ss">MM/DD/YYYY HH:mm:ss</option>
                            <option value="DD.MM.YYYY HH:mm:ss">DD.MM.YYYY HH:mm:ss</option>
                            <option value="timestamp">时间戳</option>
                        </select>
                    </div>
                    <div>
                        <button id="convertBtn" class="w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition duration-150 ease-in-out">Convert</button>
                    </div>
                    <div class="flex items-center space-x-2">
                        <input type="text" id="convertedTime" readonly class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="转换结果">
                        <button id="copy-result" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            复制
                        </button>
                    </div>
                </div>
            </div>

            <!-- Time Zones Section -->
            <div class="bg-white rounded-lg shadow-lg p-6 md:col-span-2">
                <h2 class="text-xl font-semibold mb-4 text-gray-700">Major Time Zones</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4" id="timeZones">
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const timestampInput = document.getElementById('inputTimestamp');
            const formatSelect = document.getElementById('outputFormat');
            const convertButton = document.getElementById('convertBtn');
            const convertResult = document.getElementById('convertedTime');

            // Update current timestamp
            function updateTimestamp() {
                const now = new Date();
                document.getElementById('current-timestamp-10').value = Math.floor(now.getTime() / 1000);
                document.getElementById('current-timestamp-13').value = now.getTime();
                document.getElementById('current-local-time').value = now.toLocaleString();
            }

            setInterval(updateTimestamp, 1000);
            updateTimestamp();

            // 复制功能
            function copyToClipboard(elementId) {
                const element = document.getElementById(elementId);
                element.select();
                document.execCommand('copy');
            }

            document.getElementById('copy-timestamp-10').addEventListener('click', () => copyToClipboard('current-timestamp-10'));
            document.getElementById('copy-timestamp-13').addEventListener('click', () => copyToClipboard('current-timestamp-13'));
            document.getElementById('copy-local-time').addEventListener('click', () => copyToClipboard('current-local-time'));
            document.getElementById('copy-result').addEventListener('click', () => copyToClipboard('convertedTime'));

// Timestamp converter
            document.getElementById('convertBtn').addEventListener('click', function() {
                const userInput = timestampInput.value.trim();
                const format = formatSelect.value;

                if (userInput === '') {
                    convertResult.value = '请输入有效的时间戳或日期字符串';
                    return;
                }

                let date;
                if (/^\d+$/.test(userInput)) {
                    if (userInput.length === 10) {
                        date = new Date(userInput * 1000);
                    } else if (userInput.length === 13) {
                        date = new Date(parseInt(userInput));
                    } else {
                        convertResult.value = '请输入10位或13位时间戳';
                        return;
                    }
                } else {
                    date = new Date(userInput);
                }

                if (isNaN(date.getTime())) {
                    convertResult.value = '无效的时间戳或日期字符串';
                    return;
                }

                let result;
                switch(format) {
                    case 'ISO8601':
                        result = date.toISOString();
                        break;
                    case 'US':
                        result = date.toLocaleString('en-US', {
                            year: 'numeric', month: '2-digit', day: '2-digit',
                            hour: '2-digit', minute: '2-digit', second: '2-digit',
                            hour12: true
                        });
                        break;
                    case 'UK':
                        result = date.toLocaleString('en-GB', {
                            year: 'numeric', month: '2-digit', day: '2-digit',
                            hour: '2-digit', minute: '2-digit', second: '2-digit',
                            hour12: false
                        });
                        break;
                    case 'EU':
                        result = date.toLocaleString('de-DE', {
                            year: 'numeric', month: '2-digit', day: '2-digit',
                            hour: '2-digit', minute: '2-digit', second: '2-digit',
                            hour12: false
                        });
                        break;
                    case 'RFC2822':
                        result = date.toUTCString();
                        break;
                    case 'YYYY-MM-DD HH:mm:ss':
                        result = date.getFullYear() + '-' +
                            String(date.getMonth() + 1).padStart(2, '0') + '-' +
                            String(date.getDate()).padStart(2, '0') + ' ' +
                            String(date.getHours()).padStart(2, '0') + ':' +
                            String(date.getMinutes()).padStart(2, '0') + ':' +
                            String(date.getSeconds()).padStart(2, '0')
                        break;
                    case 'MM/DD/YYYY HH:mm:ss':
                        result = date.toLocaleString('en-US', { year: 'numeric', month: '2-digit', day: '2-digit', hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false });
                        break;
                    case 'DD.MM.YYYY HH:mm:ss':
                        result = date.toLocaleString('de-DE', { year: 'numeric', month: '2-digit', day: '2-digit', hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false });
                        break;
                    case 'timestamp':
                        result = date.getTime();
                        break;
                    default:
                        result = date.toString();
                }
                convertResult.value = result;
            });

            const timeZones = [
                { name: 'UTC', zone: 'UTC' },
                { name: '北京 (UTC+8)', zone: 'Asia/Shanghai' },
                { name: 'Los Angeles (PST)', zone: 'America/Los_Angeles' },
                { name: 'New York (EST)', zone: 'America/New_York' },
                { name: 'London (GMT)', zone: 'Europe/London' },
                { name: 'Paris (CET)', zone: 'Europe/Paris' },
                { name: 'Tokyo (JST)', zone: 'Asia/Tokyo' },
                { name: 'Sydney (AEST)', zone: 'Australia/Sydney' },
                { name: 'Dubai (GST)', zone: 'Asia/Dubai' },
                { name: 'Moscow (MSK)', zone: 'Europe/Moscow' },
                { name: '孟买 (IST)', zone: 'Asia/Kolkata' },
                { name: '里约热内卢 (BRT)', zone: 'America/Sao_Paulo' }
            ];

            const timeZonesContainer = document.getElementById('timeZones');

            function updateTimeZones() {
                timeZonesContainer.innerHTML = '';
                const now = new Date();
                timeZones.forEach(tz => {
                    const timeInZone = now.toLocaleString('zh-CN', { timeZone: tz.zone, hour12: false });
                    const timestamp = Math.floor(now.getTime() / 1000);

                    const div = document.createElement('div');
                    div.className = 'bg-gray-100 p-4 rounded-md';
                    div.innerHTML = `
            <h3 class="font-semibold text-gray-700">${tz.name}</h3>
            <p class="text-sm text-gray-600">${timeInZone}</p>
            <p class="text-xs text-gray-500">Timestamp: ${timestamp}</p>
        `;
                    timeZonesContainer.appendChild(div);
                });
            }

            setInterval(updateTimeZones, 1000);
            updateTimeZones();
        });
    </script>
@endpush
