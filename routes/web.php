<?php

use App\Http\Livewire\AiDivinationPage;
use App\Http\Livewire\AiEnglish;
use App\Http\Livewire\AiPoetry;
use App\Http\Livewire\AiQuestion;
use App\Http\Livewire\AlchemyOfSoul;
use App\Http\Livewire\EmojiTranslator;
use Illuminate\Support\Facades\Route;
use App\Http\Livewire\AITranslator;
use App\Http\Controllers\ToolController;
use App\Http\Livewire\Dashboard;
use App\Http\Livewire\RepositoryForm;
use App\Http\Livewire\Auth\AdminLogin;

Route::domain(config('domains.english'))->group(function () {
    Route::get('/', AiEnglish::class);

    Route::view('/about', 'eng-about')->name('eng-about');
    Route::view('/privacy-policy', 'privacy-policy')->name('privacy-policy');
    Route::view('/terms-of-service', 'terms-of-service')->name('terms-of-service');
});


Route::view('/about', 'about')->name('about');
Route::view('/terms', 'terms')->name('terms');
Route::view('/privacy', 'privacy')->name('privacy');

Route::get('/admin-login', AdminLogin::class)->name('admin-login');

Route::middleware(['admin.auth'])->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    Route::get('/repository-form', RepositoryForm::class)->name('repository.form');
});

Route::domain(config('app.url'))->group(function () {


    Route::view('/time', 'tools.time')->name('tools.time');;

    Route::view('/image-converter', 'tools.image-converter')->name('tools.image-converter');
    Route::view('/json-beautifier', 'tools.json-beautifier')->name('tools.json-beautifier');
    Route::view('/encode', 'tools.encode')->name('tools.encode');;
    Route::view('/svg-viewer', 'tools.svg-viewer')->name('tools.svg-viewer');
    Route::view('/txt-diff', 'tools.text-diff')->name('tools.txt-diff');
    Route::view('/txt-splitter', 'tools.txt-splitter')->name('tools.txt-splitter');
    Route::view('/qr-code', 'tools.qr-code')->name('tools.qrcode');

    Route::get('/ai-divination', AiDivinationPage::class)->name('ai-divination');
    Route::get('/alchemy-of-soul', AlchemyOfSoul::class)->name('alchemy-of-soul');
    Route::get('/emoji-translator', EmojiTranslator::class)->name('emoji-translator');
    Route::get('/ai-poetry', AiPoetry::class)->name('ai-poetry');
    Route::get('/ai-translator', AITranslator::class)->name('ai-translator');
    Route::get('/ai-question', AiQuestion::class)->name('ai-question');

    Route::get('/guid', function () {
        return view('tools.guid');
    })->name('tools.guid');


    Route::get('/{category?}', [ToolController::class, 'index'])->name('tools.index');

});



