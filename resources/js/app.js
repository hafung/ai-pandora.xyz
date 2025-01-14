require('./bootstrap');
import './modal-manager';

// window.generateUniqueId = function() {
//     const generateFingerprint = () => {
//         const screen = window.screen;
//         const nav = window.navigator;
//
//         let fingerprint = '';
//         fingerprint += screen.height + 'x' + screen.width + 'x' + screen.colorDepth;
//         fingerprint += nav.language || nav.userLanguage;
//         fingerprint += nav.hardwareConcurrency;
//         fingerprint += nav.deviceMemory;
//         fingerprint += nav.platform;
//         fingerprint += nav.userAgent;
//
//         // 添加已安装的插件信息
//         const plugins = Array.from(nav.plugins).map(p => p.name).join(',');
//         fingerprint += plugins;
//
//         return fingerprint;
//     };
//
//     const fingerprint = generateFingerprint();
//     const timestamp = new Date().getTime();
//     const randomPart = Math.random().toString(36).substr(2, 8);
//
//     const uniqueId = btoa(fingerprint + timestamp + randomPart).replace(/[/+=]/g, '').substr(0, 32);
//
//     return uniqueId;
// }

// window.generateUniqueId = (function() {
//     let cachedId = null;
//     const cacheExpiration = 24 * 60 * 60 * 1000; // 24 hours in milliseconds
//
//     function generateFingerprint() {
//         const components = [
//             navigator.userAgent,
//             navigator.language,
//             new Date().getTimezoneOffset(),
//             !!navigator.plugins.length,
//             !!navigator.doNotTrack,
//             screen.colorDepth,
//             screen.width + 'x' + screen.height,
//             navigator.hardwareConcurrency,
//             navigator.deviceMemory,
//             navigator.platform
//         ];
//
//         if (navigator.plugins) {
//             components.push(Array.from(navigator.plugins, p => p.name).join(','));
//         }
//
//         try {
//             const canvas = document.createElement('canvas');
//             const gl = canvas.getContext('webgl');
//             if (gl) {
//                 components.push(gl.getParameter(gl.VENDOR));
//                 components.push(gl.getParameter(gl.RENDERER));
//             }
//         } catch (e) {
//             components.push('canvas-error');
//         }
//
//         return components.join('|');
//     }
//
//     function hash(str) {
//         let hash = 0;
//         for (let i = 0; i < str.length; i++) {
//             const char = str.charCodeAt(i);
//             hash = ((hash << 5) - hash) + char;
//             hash = hash & hash; // Convert to 32bit integer
//         }
//         return hash;
//     }
//
//     return function() {
//         const now = Date.now();
//         if (cachedId && now - cachedId.timestamp < cacheExpiration) {
//             return cachedId.id;
//         }
//
//         const fingerprint = generateFingerprint();
//         const timestamp = now.toString(36);
//         const randomPart = Math.random().toString(36).substr(2, 8);
//
//         const uniqueId = hash(fingerprint + timestamp + randomPart).toString(36);
//         cachedId = { id: uniqueId, timestamp: now };
//
//         return uniqueId;
//     };
// })();


window.generateUniqueId = (function() {
    let cachedId = null;
    const cacheExpiration = 24 * 60 * 60 * 1000; // 24 hours in milliseconds

    function generateFingerprint() {
        const components = [
            navigator.userAgent,
            navigator.language,
            new Date().getTimezoneOffset(),
            !!navigator.plugins.length,
            !!navigator.doNotTrack,
            screen.colorDepth,
            `${screen.width}x${screen.height}`,
            navigator.hardwareConcurrency
        ];

        // 安全地检查 deviceMemory 属性
        if ('deviceMemory' in navigator) {
            components.push(navigator.deviceMemory);
        }

        components.push(navigator.platform);

        if (navigator.plugins) {
            components.push(Array.from(navigator.plugins, p => p.name).join(','));
        }

        try {
            const canvas = document.createElement('canvas');
            const gl = canvas.getContext('webgl');
            if (gl) {
                components.push(gl.getParameter(gl.VENDOR));
                components.push(gl.getParameter(gl.RENDERER));
            }
        } catch (e) {
            components.push('canvas-error');
        }

        return components.join('|');
    }

    function hash(str) {
        let hash = 0;
        for (let i = 0; i < str.length; i++) {
            const char = str.charCodeAt(i);
            hash = ((hash << 5) - hash) + char;
            hash = hash & hash; // Convert to 32bit integer
        }
        return hash;
    }

    return function() {
        const now = Date.now();
        if (cachedId && now - cachedId.timestamp < cacheExpiration) {
            return cachedId.id;
        }

        const fingerprint = generateFingerprint();
        const timestamp = now.toString(36);
        const randomPart = Math.random().toString(36).substr(2, 8);

        const uniqueId = hash(fingerprint + timestamp + randomPart).toString(36);
        cachedId = { id: uniqueId, timestamp: now };

        return uniqueId;
    };
})();

window.getOrCreateUniqueId = function() {
    let uniqueId = localStorage.getItem('uniqueUserId');

    if (!uniqueId) {
        uniqueId = generateUniqueId();
        localStorage.setItem('uniqueUserId', uniqueId);
    }

    return uniqueId;
}


// 在需要时，可以这样将ID发送到服务器
// fetch('/api/some-endpoint', {
//     method: 'POST',
//     headers: {
//         'Content-Type': 'application/json',
//     },
//     body: JSON.stringify({ userId: userId }),
// });

function generateRandomAvatar() {
    const colors = ['#FF6633', '#FFB399', '#FF33FF', '#FFFF99', '#00B3E6', '#E6B333', '#3366E6', '#999966', '#99FF99', '#B34D4D'];
    const backgroundColor = colors[Math.floor(Math.random() * colors.length)];
    const faceColor = '#FDB797'; // 一个通用的肤色
    const eyeColor = '#36454F'; // 深灰色眼睛
    const mouthColor = '#FF9999'; // 浅粉色嘴巴

    let svgString = `
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100">
            <!-- 背景 -->
            <rect width="100" height="100" fill="${backgroundColor}"/>

            <!-- 脸 -->
            <circle cx="50" cy="50" r="35" fill="${faceColor}"/>

            <!-- 左眼 -->
            <circle cx="35" cy="45" r="5" fill="${eyeColor}"/>

            <!-- 右眼 -->
            <circle cx="65" cy="45" r="5" fill="${eyeColor}"/>

            <!-- 嘴巴 -->
            <path d="M 35 65 Q 50 80 65 65" fill="none" stroke="${mouthColor}" stroke-width="3"/>

            <!-- 随机头发 -->
            ${generateRandomHair()}
        </svg>
        `;

    return `data:image/svg+xml;charset=utf-8,${encodeURIComponent(svgString)}`;
}

function generateRandomHair() {
    const hairColors = ['#4A4A4A', '#8A3324', '#B8860B', '#CD853F', '#D2691E', '#556B2F'];
    const hairColor = hairColors[Math.floor(Math.random() * hairColors.length)];
    const hairStyles = [
        `<path d="M 15 50 Q 30 10 50 15 Q 70 10 85 50" fill="${hairColor}"/>`, // 长发
        `<path d="M 25 30 Q 50 10 75 30" fill="${hairColor}"/>`, // 短发
        `<rect x="20" y="10" width="60" height="30" rx="10" ry="10" fill="${hairColor}"/>`, // 平头
        `<path d="M 20 50 Q 50 -10 80 50" fill="${hairColor}"/>` // 蓬松卷发
    ];

    return hairStyles[Math.floor(Math.random() * hairStyles.length)];
}

window.getOrCreateAvatar = function () {
    const uniqueId = getOrCreateUniqueId();
    let avatarSvg = localStorage.getItem('avatar_' + uniqueId);

    if (!avatarSvg) {
        avatarSvg = generateRandomAvatar();
        localStorage.setItem('avatar_' + uniqueId, avatarSvg);
    }

    return avatarSvg;
}

function areCookiesEnabled() {
    try {
        document.cookie = "testcookie=1; SameSite=Strict;";
        const ret = document.cookie.indexOf("testcookie=") !== -1;
        document.cookie = "testcookie=1; SameSite=Strict; expires=Thu, 01-Jan-1970 00:00:01 GMT";
        return ret;
    } catch (e) {
        return false;
    }
}

// 设置自定义参数 cookie
function setFingerprintCookie() {
    if (!areCookiesEnabled()) {
        console.warn('Cookies are not enabled. Custom parameter cannot be set.');
        return;
    }

    try {
        const Fingerprint = window.getFingerprint(); // 假设这个函数已经定义
        // console.log(Fingerprint)
        // document.cookie = `browser_fingerprint=${encodeURIComponent(Fingerprint)}; path=/; max-age=3600; SameSite=Strict`; // 1小时过期
        document.cookie = `browser_fingerprint=${Fingerprint}; path=/; max-age=3600; SameSite=Strict`; // 1小时过期
    } catch (error) {
        console.error('Error setting custom parameter cookie:', error);
    }
}

// 获取自定义参数（如果还没有定义）
window.getFingerprint = window.getFingerprint || function() {
    // 这里生成或获取您的自定义参数值
    // return 'your_frontend_generated_value';

    return getOrCreateUniqueId()
};

// 更新自定义参数 cookie
window.updateCustomParamCookie = setFingerprintCookie;

// console.log('Fingerprint2223333')

// 页面加载时设置 cookie
document.addEventListener('DOMContentLoaded', setFingerprintCookie);

// document.addEventListener('livewire:load', setFingerprintCookie);

// console.log(document.cookie);


window.showToast = window.showToast || function(message, type = 'info') {
    const container = document.getElementById('toast-container');
    const toast = document.createElement('div');
    toast.className = `mb-4 p-4 rounded-lg shadow-lg transition-all duration-300 ${
        type === 'success' ? 'bg-green-500' :
            type === 'error' ? 'bg-red-500' :
                'bg-blue-500'
    } text-white max-w-md w-full mx-auto`;
    toast.textContent = message;

    container.appendChild(toast);

    // 添加一个小延迟，然后应用 transform 来创建一个滑入效果
    setTimeout(() => {
        toast.style.transform = 'translateY(0)';
        toast.style.opacity = '1';
    }, 10);

    setTimeout(() => {
        toast.style.transform = 'translateY(-20px)';
        toast.style.opacity = '0';
        setTimeout(() => {
            container.removeChild(toast);
        }, 300);
    }, 2500);
}


// const Loading = {
//     show() {
//         document.getElementById('global-loading').classList.remove('hidden');
//     },
//     hide() {
//         document.getElementById('global-loading').classList.add('hidden');
//     }
// };
//
// window.Loading = window.Loading || Loading;
