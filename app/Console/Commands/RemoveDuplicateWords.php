<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class RemoveDuplicateWords extends Command
{
    /**
     * php artisan words:remove-duplicates
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'words:remove-duplicates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove duplicate words from eng_words.txt file';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
//        $filePath = 'eng_words.txt';
        $filePath = resource_path('data/eng_words.txt');
        if (!File::exists($filePath)) {
            $this->error("File not found: {$filePath}");
            return 1;
        }

        $this->info('Starting to remove duplicate words...');

        // 读取文件内容
        $content = File::get($filePath);

//        $content = Storage::get($filePath);
//        if ($content === false) {
//            $this->error("Unable to read the file: {$filePath}");
//            return 1;
//        }

        // 将内容分割成数组并去重
        $words = array_filter(explode("\n", $content)); // 移除空行
        $uniqueWords = array_unique($words);

        // 统计去重前后的单词数量
        $originalCount = count($words);
        $uniqueCount = count($uniqueWords);

        // 将去重后的内容写回文件
        $newContent = implode("\n", $uniqueWords);
        File::put($filePath, $newContent);

        $this->info("Duplicate words removed successfully.");
        $this->info("Original word count: {$originalCount}");
        $this->info("Unique word count: {$uniqueCount}");
        $this->info("Removed " . ($originalCount - $uniqueCount) . " duplicate words.");

        return 0;

        // 将内容分割成数组并去重
//        $words = explode("\n", $content);
//        $uniqueWords = array_unique($words);
//
//        // 统计去重前后的单词数量
//        $originalCount = count($words);
//        $uniqueCount = count($uniqueWords);
//
//        // 将去重后的内容写回文件
//        $newContent = implode("\n", $uniqueWords);
//        if (Storage::put($filePath, $newContent) === false) {
//            $this->error("Unable to write to the file: {$filePath}");
//            return 1;
//        }
//
//        $this->info("Duplicate words removed successfully.");
//        $this->info("Original word count: {$originalCount}");
//        $this->info("Unique word count: {$uniqueCount}");
//        $this->info("Removed " . ($originalCount - $uniqueCount) . " duplicate words.");
//
//        return 0;
    }
}
