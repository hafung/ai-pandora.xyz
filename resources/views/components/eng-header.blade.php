<style>
    @keyframes breathe {
        0%, 100% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.05);
        }
    }

    .logo-text {
        font-family: 'Orbitron', sans-serif;
        font-size: 1.5rem;
        font-weight: 700;
        color: #4A00E0;
        text-transform: uppercase;
        letter-spacing: 2px;
        position: relative;
        display: inline-block;
        padding: 5px 10px;
        background: linear-gradient(45deg, #4A00E0, #00E5FF);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        text-shadow: 0 0 10px rgba(74, 0, 224, 0.3);
        animation: breathe 3s ease-in-out infinite;
    }

    .logo-text::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(45deg, #4A00E0, #00E5FF);
        opacity: 0.3;
        filter: blur(10px);
        z-index: -1;
    }

    .logo-text::after {
        content: attr(data-text);
        position: absolute;
        top: 0;
        left: 0;
        z-index: -2;
        padding: 5px 10px;
        color: #00E5FF;
        filter: blur(4px);
    }

    .header-bg {
        background-color: #f0f4f8; /* 淡雅的浅蓝灰色 */
    }

    #avatar {
        transition: transform 0.3s ease;
    }

    #avatar:hover {
        transform: scale(1.1);
    }

    .avatar-container {
        position: relative;
        display: inline-block;
    }

    .avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .dropdown-content {
        /*display: none;*/
        position: absolute;
        right: 0;
        background-color: #f9f9f9;
        min-width: 120px;
        box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
        z-index: 1;
        /*border-radius: 4px;*/
        border-radius: 8px;
        padding: 12px;
        font-size: 14px;
        visibility: hidden;
        transition: all 0.3s ease;
    }

    .avatar-container:hover .dropdown-content {
        opacity: 1;
        visibility: visible;
        animation: fadeIn 0.3s;
    }

    .avatar-container:hover .avatar {
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
    }

    .dropdown-content p {
        /*margin: 5px 0;*/
        color: #333;
        margin: 8px 0;
        padding: 0;
        white-space: nowrap;
    }

    .dropdown-content span {
        font-weight: bold;
        color: #4a90e2;
    }
</style>

<header class="header-bg shadow-sm">
    <div class="max-w-9xl mx-auto px-4 sm:px-6 lg:px-8 py-2 flex justify-between items-center">
        <div class="flex items-center">
            <!-- Logo container that works for both SVG and image -->
            <div class="h-8 w-auto">
                <span class="logo-text" data-text="AI英语卡片">AI英语卡片</span>
            </div>
        </div>
        <nav class="flex items-center space-x-4">

            <div class="avatar-container">
                <div id="avatar" class="w-8 h-8 rounded-full overflow-hidden cursor-pointer"></div>
                <div class="dropdown-content">
                    <p>累计查询：<span id="query-count">n</span></p>
                    <p>进步指数：<span id="progress-index">m</span></p>
                </div>
            </div>
        </nav>
    </div>
</header>
