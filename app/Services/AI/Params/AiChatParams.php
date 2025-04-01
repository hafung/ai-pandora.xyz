<?php

namespace App\Services\AI\Params;

class AiChatParams {

    private $user_prompt;
    private bool $use_context = true;
    private bool $enable_search = false;
    private $system_prompt;
    private $model;
    private $mode;
    /**
     * @var array
     */
    private $messages;
    private $temperature;
    private $user;
    private $checkpoint;
    private $images;
    private $embedding;

    public function __construct(array $params = []) {

        $this->setTemperature($params['temperature'] ?? 0.7);

        if (!empty($params['enable_search'])) {
            $this->setEnableSearch(true);
        }

        if (isset($params['use_context'] )) {
            $this->setUseContext((bool)$params['use_context']);
        }

        $this->setSystemPrompt(empty($params['system_prompt']) ? '' : $params['system_prompt']);

        $this->setImages($params['images'] ?? []);

        if (!empty($params['model'])) {
            $this->setModel($params['model']);
        }

        if (!empty($params['user_prompt'])) {
            $this->setUserPrompt($params['user_prompt']);
        }

        if (!empty($params['messages'])) {
            $this->setMessages($params['messages']);
        }

        if (!empty($params['user'])) {
            $this->setUser($params['user']);
        }

        if (!empty($params['mode'])) {
            $this->setMode($params['mode']);
        }

        if (!empty($params['checkpoint'])) {
            $this->setCheckpoint($params['checkpoint']);
        }

        if (!empty($params['embedding'])) {
            $this->setEmbedding($params['embedding']);
        }
    }

    public function setUserPrompt($prompt): AiChatParams {
        $this->user_prompt = $prompt;
        return $this;
    }

    public function setSystemPrompt($prompt): AiChatParams {
        $this->system_prompt = $prompt;
        return $this;
    }

    public function setModel($model): AiChatParams {
        $this->model = $model;
        return $this;
    }

    public function setMessages(array $messages): AiChatParams {
        $this->messages = $messages;
        return $this;
    }

    public function setTemperature($temperature): AiChatParams {
        $this->temperature = $temperature;
        return $this;
    }

    public function getUserPrompt() {
        return $this->user_prompt;
    }

    public function getSystemPrompt() {
        return $this->system_prompt;
    }

    public function getModel() {
        return $this->model;
    }

    public function getMessages() {
        return $this->messages;
    }

    public function getTemperature() {
        return $this->temperature;
    }

    public function getImages() {
        return $this->images;
    }

    private function setMode($mode) {
        $this->mode = $mode;
    }

    public function getMode() {
        return $this->mode;
    }

    private function setUser($user) {
        $this->user = $user;
    }

    public function getUser() {
        return $this->user;
    }

    private function setCheckpoint($checkpoint) {
        $this->checkpoint = $checkpoint;
    }

    public function getCheckpoint() {
        return $this->checkpoint;
    }


    private function setImages($param) {
        $this->images = $param;
    }

    private function setEmbedding($embedding) {
        $this->embedding = $embedding;
    }
    public function getEmbedding() {
        return $this->embedding;
    }

    public function getUseContext() {
        return $this->use_context;
    }

    public function toArray(): array {
        return [
            'user_prompt' => $this->getUserPrompt(),
            'system_prompt' => $this->getSystemPrompt(),
            'model' => $this->getModel(),
            'messages' => $this->getMessages(),
            'temperature' => $this->getTemperature(),
            'mode' => $this->getMode(),
            'user' => $this->getUser(),
            'checkpoint' => $this->getCheckpoint()
        ];
    }

    private function setUseContext($param) {
        $this->use_context = $param;
    }

    private function setEnableSearch(bool $status) {
        $this->enable_search = $status;
    }


}
