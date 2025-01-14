<?php

namespace App\Services\AI;

class ImageParams {

    const WUJIE_COMMON_MODEL_CODE = 127;

    private $width;
    private $height;
    private $num;
    private $model = self::WUJIE_COMMON_MODEL_CODE; // model_code  127
    private $prompt;


    private $samplerIndex = 16; //integer <int32> 采样模式是指扩散去噪算法的采样模式，如果设置正确，它们会发散并最终收敛不同model支持的采样模式不同，可通过获取作画模型的预设资源(/ai/default_resource)接口查询，字段路径：data->create_option_menu->sampler_models->sampler_index示例值:17

    private $clipSkip = 2; //integer <int32> 画面描述匹配度，取值范围 [1～12] (DD不支持)。描述画面的准确程度与数值大小成反比，数值越小表示对图像的控制度越高，最佳使用区间为1-2。

    private $prefineMultiple = 1; // 图片精绘倍数，默认不精绘，可传小数，取值范围为[1~2]。精绘是另一种将图片放大的方式，需要重新绘制一遍图片，会有较长的额外耗时，在细节刻画上表现更出色，也会消耗更多积分。注：目前也支持精绘+超分组合使用（仅支持先精绘后超分），但仅支持2倍超分。

    private $superSizeMultiple = 2; //图片超分倍数，默认不超分，可传小数，取值范围为[1-4]。


    public function __construct(array $params = []) {

        if (isset($params['prompt'])) {
            $this->setPrompt($params['prompt']);
        }
        if (isset($params['num'])) {
            $this->setNum($params['num']);
        }
        if (isset($params['width'])) {
            $this->setWidth($params['width']);
        }
        if (isset($params['height'])) {
            $this->setHeight($params['height']);
        }
        if (isset($params['sampler_index'])) {
            $this->setSamplerIndex($params['sampler_index']);
        }
        if (isset($params['clip_skip'])) {
            $this->setClipSkip($params['clip_skip']);
        }
        if (isset($params['prefine_multiple'])) {
            $this->setPrefineMultiple($params['prefine_multiple']);
        }
        if (isset($params['super_size_multiple'])) {
            $this->setSuperSizeMultiple($params['super_size_multiple']);
        }

    }

    public function setPrompt($prompt): ImageParams {
        $this->prompt = $prompt;
        return $this;
    }


    public function setModel($model): ImageParams {
        $this->model = $model;
        return $this;
    }

    private function setSamplerIndex($sampler_index): void {
        $this->samplerIndex = $sampler_index;
    }

    private function setClipSkip($clip_skip): ImageParams {
        $this->clipSkip = $clip_skip;
        return $this;
    }

    public function getPrompt() {
        return $this->prompt;
    }


    public function getModel() {
        return $this->model;
    }

    // setNum
    public function setNum($num) {
        $this->num = $num;
        return $this;
    }

    public function getNum() {
        return $this->num;
    }

    public function setWidth($width): ImageParams {
        $this->width = $width;
        return $this;
    }

    public function getWidth() {
        return $this->width;
    }

    public function setHeight($height): ImageParams {
        $this->height = $height;
        return $this;
    }

    public function getHeight() {
        return $this->height;
    }

    public function getSamplerIndex(): int {
        return $this->samplerIndex;
    }

    public function getClipSkip() {
        return $this->clipSkip;
    }

    public function getPrefineMultiple() {
        return $this->prefineMultiple;
    }

    public function getSuperSizeMultiple() {
        return $this->superSizeMultiple;
    }

    public function setSuperSizeMultiple($superSizeMultiple): ImageParams {
        $this->superSizeMultiple = $superSizeMultiple;
        return $this;
    }

    private function setPrefineMultiple($prefine_multiple): ImageParams {
        $this->prefineMultiple = $prefine_multiple;
        return $this;
    }


    public function toArray(): array {
        return [
            'prompt' => $this->getPrompt(),
            'model' => $this->getModel(),
            'num' => $this->getNum(),
            'width' => $this->getWidth(),
            'height' => $this->getHeight(),
            'sampler_index' => $this->getSamplerIndex(),
            'clip_skip' => $this->getClipSkip(),

//            'prefine_multiple' => $this->getPrefineMultiple(),
//            'super_size_multiple' => $this->getSuperSizeMultiple(),

//            'init_image_url' => $this->getInitImageUrl(),
//            'init_width' => $this->getInitWidth(),
//            'init_height' => $this->getInitHeight(),
//            'creativity_degree' => $this->getCreativityDegree(),
//            'image_type' => $this->getImageType(),
//            'style' => $this->getStyle(),
//            'artists' => $this->getArtists(),
//            'style_decoration' => $this->getStyleDecoration(),
//            'steps' => $this->getSteps(),
//            'cfg' => $this->getCfg(),
//            'create_source' => $this->getCreateSource(),
        ];
    }



}
