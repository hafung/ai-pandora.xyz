<?php

namespace App\Http\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminLogin extends Component {
    public $username;
    public $password;
    public bool $remember = false;
    public string $errorMessage = '';

    // Add mount method to check login status when component loads
    public function mount()
    {
        // Check if user is already logged in
        if (session('admin_logged_in')) {
            return redirect()->intended('/dashboard');
        }
    }

    public function login() {
//        $credentials = [
//            'username' => $this->username,
//            'password' => $this->password,
//        ];

        // 硬编码验证
//        todo 自己定义 或 使用数据库
        if ($this->username === config('app.admin_username') && $this->password === config('app.admin_password')) {
            session(['admin_logged_in' => true]); // 使用 Session 标记登录状态
            return redirect()->intended('/dashboard'); // 登录成功，重定向到仪表盘
        }

        $this->errorMessage = 'Invalid credentials.';
        $this->password = ''; // 清空密码
    }

    public function render() {
        return view('livewire.auth.admin-login');
    }
}
