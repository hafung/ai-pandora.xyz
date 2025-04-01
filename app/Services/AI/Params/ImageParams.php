<?php

namespace App\Services\AI\Params;

class ImageParams {

    const WUJIE_COMMON_MODEL_CODE = 127;

    //支持自定义尺寸，尺寸要求为64的倍数，最小尺寸为64，尺寸不为64的倍数的，会向64对齐， 例96对齐64， 97对齐到128。
    //默认尺寸最长边限制在1344以内。
    //若使用精绘，长边需限制在1024以内，分辨率限制在1024*576以内。
    //使用controlnet或者createSource=1时可不传，其余情况下必填。
    //使用cutdiffion单边范围为【1088，3072】,分辨率乘积不超过5017600。
    private $width;
    private $height;
    private $num;
    private $model = self::WUJIE_COMMON_MODEL_CODE; // model_code  127
    private $prompt;
    private $aspectRatio;

//    private $createSource; // 图生图相关

    private $samplerIndex = 16; //integer <int32> 采样模式是指扩散去噪算法的采样模式，如果设置正确，它们会发散并最终收敛不同model支持的采样模式不同，可通过获取作画模型的预设资源(/ai/default_resource)接口查询，字段路径：data->create_option_menu->sampler_models->sampler_index示例值:17

    private $clipSkip = 2; //integer <int32> 画面描述匹配度，取值范围 [1～12] (DD不支持)。描述画面的准确程度与数值大小成反比，数值越小表示对图像的控制度越高，最佳使用区间为1-2。

    private $prefineMultiple = 1; // 图片精绘倍数，默认不精绘，可传小数，取值范围为[1~2]。精绘是另一种将图片放大的方式，需要重新绘制一遍图片，会有较长的额外耗时，在细节刻画上表现更出色，也会消耗更多积分。注：目前也支持精绘+超分组合使用（仅支持先精绘后超分），但仅支持2倍超分。

    private $superSizeMultiple = 2; //图片超分倍数，默认不超分，可传小数，取值范围为[1-4]。
    //注：宽高超分倍数一致，向下取整。例如宽高为512，超分1.9倍，出图宽高为972。超分的额外耗时较短，效果仅是单纯提高图片分辨率，不会变更AI出图后的内容。
    //ControlNet下该参数不需要指定，其超分由底图宽高决定（长边<=2048时，默认超分到2048，大于时，底图会被缩放到1024，再超分两倍）。

    private $initImageUrl; // 图生图底图URL。
    private $initWidth; // 底图宽度 使用ControlNet或createSource=1时必填。
    private $initHeight; // 底图高度 使用ControlNet或createSource=1时必填。
    private $creativityDegree;  // 默认50 创意度越低，生成的图片越接近参考图。 使用底图（即init_image_url有值）时才会生效。
    private $imageType; // array[string] 画面类型，限制数量不超过10个。 可通过获取作画模型的预设资源(ai/default_resource)接口查询，获取返回列表中的画面类型（image_type-name）字段。
    private $style; // array[string] 风格，限制数量不超过10个。可通过获取作画模型的预设资源(ai/default_resource)接口查询，获取返回列表中的风格（style-name）字段。
    private $artists; // array[string] 风格，限制数量不超过10个。可通过获取作画模型的预设资源(ai/default_resource)接口查询，获取返回列表中的艺术家（artist-name）字段。示例值:[鸟山明]
    private $styleDecoration; // 风格选择参数，限制数量不超过10个。可通过获取作画模型的预设资源(ai/default_resource)接口查询，获取返回列表中的元素魔法（style_decoration-key）字段。imageType，style，artist，elementMagic本质上也都属于styleDecoration。示例值:星空法]
    private $cfg; // number <float>  default is 7, 提示词相关性（CFG scale），取值范围[1-30]，默认值7。表示AI对描述参数的倾向程度，数值越大会越专注于提示词的内容，生成更加符合描述的图像

    public function __construct(array $params = []) {

        if (isset($params['prompt'])) {
            $this->setPrompt($params['prompt']);
        }
        if (isset($params['aspect_ratio'])) {
            $this->setAspectRatio($params['aspect_ratio']);
        }
        if (isset($params['num'])) {
            $this->setNum($params['num']);
        }
        if (isset($params['n'])) {
            $this->setNum($params['n']);
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

    public function setAspectRatio($aspect_ratio): ImageParams {
        $this->aspectRatio = $aspect_ratio;
        return $this;
    }
    public function getAspectRatio() {
        return $this->aspectRatio;
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
