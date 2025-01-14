<?php

namespace App\Services\AI\Contracts;

use App\Services\AI\Results\MusicResult;

interface MusicAiServiceInterface {

    public function generateMusicByPrompt(string $prompt): array;

//    public function generateMusic(string $title, string $tags, string $lyric = ''): array; // songIds
    public function generateMusic(string $lyric, string $tags, string $title = ''): array; // songIds
//    public function generateMusic(string $title, string $tags, string $lyric): array; // songIds

//    public function queryTask(string $songIds): MusicResult;

    /**
     * get music task result.
     *
     * @param string $songIds
     * @return MusicResult[]
     */
    public function queryTask(string $songIds): array;

}
