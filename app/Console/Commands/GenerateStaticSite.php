<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class GenerateStaticSite extends Command
{
    protected $signature = 'site:generate'; // php artisan site:generate
    protected $description = 'Generate static HTML files from Blade templates';

    public function handle()
    {
        $pages = [
            'about' => 'about.html',
            'privacy-policy' => 'privacy-policy.html',
            'terms-of-service' => 'terms-of-service.html',
        ];

        foreach ($pages as $route => $filename) {
            $content = view($route)->render();
            File::put(public_path($filename), $content);
            $this->info("Generated {$filename}");
        }

        $this->info('Static site generation completed!');
    }
}
