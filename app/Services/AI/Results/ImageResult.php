<?php

namespace App\Services\AI\Results;

use JsonSerializable;


class ImageResult implements JsonSerializable {
    public $url;
    public $width;
    public $height;

    public function __construct(string $url, string $format, int $width, int $height) {
        $this->url = $url;
        $this->width = $width;
        $this->height = $height;
    }

    public function jsonSerialize() {
        return [
            'url' => $this->url,
            'width' => $this->width,
            'height' => $this->height,
        ];
    }
}
