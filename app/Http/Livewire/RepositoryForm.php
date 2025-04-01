<?php

namespace App\Http\Livewire;

use App\Jobs\ProcessWeChatArticle;
use Exception;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use EasyWeChat\OfficialAccount\Application;

class RepositoryForm extends Component {

    public string $type = 'github'; // 'github' 或 'news'
    public string $url = '';
//    public string $title = '';
    public string $content = '';
    public string $successMessage = '';
    public bool $publishImmediately = false;

    protected array $rules = [
//        'type' => 'required|in:github,news,quotes,stories',
        'type' => 'required|in:github,news,quotes,stories,micro_fiction,urban_romance,toxic_chicken_soup',
        'url' => 'nullable|url|max:128|required_without:content',
        'content' => 'nullable|string|max:1024|required_without:url'
    ];


    public function saveRepository() {

//        // 使用 文件 存储
////        $filePath = 'repositories.json';
////        if (Storage::disk('local')->exists($filePath)) {
////            $existingData = json_decode(Storage::disk('local')->get($filePath), true);
////        } else {
////            $existingData = [];
////        }
////        $existingData[] = $data;
////        Storage::disk('local')->put($filePath, json_encode($existingData, JSON_PRETTY_PRINT));

        try {

            $this->validate();

            $data = [
                'id' => Str::uuid()->toString(),
                'type' => $this->type,
                'url' => $this->url,
//                'title' => $this->title,
                'content' => $this->content,
                'publishImmediately' => $this->publishImmediately
            ];

            ProcessWeChatArticle::dispatch($data);

            $this->emit('showToast', 'Repository saved successfully!', 'success', 2500);

            $this->reset(['url', 'content']);

        } catch (Exception $e) {
            // Log any exceptions that occur
            Log::error('Error saving repository', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            $this->emit('showToast', $e->getMessage(), 'error', 2500);
        }

    }

    public function render() {
        return view('livewire.repository-form')->extends('layouts.admin');
    }
}
