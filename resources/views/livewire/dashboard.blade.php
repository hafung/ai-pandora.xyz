@extends('layouts.admin')  <!-- 确保使用了你的布局 -->

@section('content')
    <div class="p-6">
        <h1 class="text-2xl font-bold">Dashboard</h1>
        <p>Welcome to the admin dashboard!</p>
        <!-- 菜单 -->
        <div class="mt-8">
            <h2 class="text-xl font-semibold mb-4">Menu</h2>
            <ul class="menu bg-base-100 w-56 p-2 rounded-box">
                <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li><a href="{{ route('repository.form') }}">Repository Form</a></li>
                <li><a href="{{ route('article-generator') }}">Article Generator</a></li>
            </ul>
        </div>
    </div>
@endsection
