<?php

namespace App\Services\AI\Results;

use JsonSerializable;

class MusicResult implements JsonSerializable {

    public $id;
    public $audioUrl;
    public $format;
    public $title;
    public $cover;
    public $status;
    public $lyric;
    public $metaData;

    private string $videoUrl;

    public function __construct(string $id, string $url, string $cover, string $lyric, string $videoUrl = '') {
        $this->id = $id;
        $this->audioUrl = $url;
        $this->cover = $cover;
        $this->lyric = $lyric;
        $this->videoUrl = $videoUrl;
    }

    public function jsonSerialize() {
        return [
            'id' => $this->id,
            'cover' => $this->cover,
            'audio_url' => $this->audioUrl,
            'lyric' => $this->lyric,
            'video_url' => $this->videoUrl
        ];
    }
}
