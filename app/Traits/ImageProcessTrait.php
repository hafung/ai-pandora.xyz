<?php

namespace App\Traits;

trait ImageProcessTrait {

    private function calculateSizeFromAspectRatio(string $aspectRatio, $adapter = 'dalle3'): string {

        $landscapeSize = '1792x1024'; // 横幅尺寸
        $portraitSize = '1024x1792';  // 竖向尺寸
        $squareSize = '1024x1024';  // 方形尺寸

        switch ($aspectRatio) {
            case 'ASPECT_4_3':
            case 'ASPECT_16_10':
            case 'ASPECT_3_1':
            case 'ASPECT_16_9':
                return $adapter === 'dalle3' ? $landscapeSize : '1024x576';
            case 'ASPECT_3_2':
                return $adapter === 'dalle3' ? $landscapeSize : '768x512';
            case 'ASPECT_10_16':
            case 'ASPECT_9_16':
            case 'ASPECT_3_4':
            case 'ASPECT_1_3':
            case 'ASPECT_2_3':
                return $adapter === 'dalle3' ? $portraitSize : '768x1024';
            default:
                return $squareSize;
        }

    }

}
