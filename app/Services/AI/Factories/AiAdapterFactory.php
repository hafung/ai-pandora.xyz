<?php

namespace App\Services\AI\Factories;

use App\Services\AI\Contracts\AiAdapterInterface;
use Exception;
use App\Services\AI\Adapters\IflytekPPTAiAdapter;
use App\Services\AI\Adapters\OpenAiAdapter;
use App\Services\AI\Adapters\WuJieAiAdapter;
use InvalidArgumentException;
use App\Services\AI\Adapters\SwooleAiAdapter;
use App\Services\AI\Adapters\FoxAiSunoMusicAdapter;


class AiAdapterFactory {

    private static ?AiAdapterFactory $instance = null;
    private array $adapterInstances = [];

    private function __construct() {
    }

    public static function getInstance(): AiAdapterFactory {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }


//    public function getAdapter(string $provider, string $apiKey = '', string $host = ''): AiAdapterInterface {
    public function getAdapter(string $provider): AiAdapterInterface {
        $adapterClass = $this->resolveAdapterClass($provider);
//        $key = $this->generateKey($adapterClass, $apiKey, $host, $provider);
        $key = $this->generateKey($adapterClass, $provider);

        if (!isset($this->adapterInstances[$key])) {
//            $this->adapterInstances[$key] = $this->createAdapter($adapterClass, $provider, $apiKey, $host);
            $this->adapterInstances[$key] = $this->createAdapter($adapterClass, $provider);
        }

        return $this->adapterInstances[$key];
    }

    private function resolveAdapterClass($provider): string {

        if ($provider === 'swoole') {
            return SwooleAiAdapter::class;
        }
        return OpenAiAdapter::class;

    }

//    private function createAdapter(string $adapterClass, string $provider = '', string $apiKey = '', string $host = ''): AiAdapterInterface {
//    private function createAdapter(string $adapterClass, string $provider, string $apiKey = '', string $host = ''): AiAdapterInterface {
    private function createAdapter(string $adapterClass, string $provider): AiAdapterInterface {
        if (!is_subclass_of($adapterClass, AiAdapterInterface::class)) {
            throw new InvalidArgumentException("$adapterClass must implement AiAdapterInterface");
        }

        switch ($adapterClass) {
            case SwooleAiAdapter::class:
//                return new SwooleAiAdapter($apiKey);
                return new SwooleAiAdapter();
            case OpenAiAdapter::class:
//                return new OpenAiAdapter($apiKey ?: config("ai.{$provider}_api_key"), $host ?: config("ai.{$provider}_api_host"), $provider);
//                return new OpenAiAdapter($provider, $apiKey ?: config("ai.{$provider}_api_key"), $host ?: config("ai.{$provider}_api_host"));
                return new OpenAiAdapter($provider);
            default:
//                return new $adapterClass($apiKey, $host, $provider);
                return new $adapterClass($provider);
        }
    }


//    private function generateKey(string $adapterType, string $apiKey, string $host, string $provider): string {
    private function generateKey(string $adapterType, string $provider): string {
//        return md5($adapterType . $apiKey . $host . $provider);
        return md5($adapterType . $provider);
    }

    // 防止克隆
    private function __clone() {
    }

    // 防止反序列化
    public function __wakeup() {
        throw new Exception("Cannot unserialize singleton");
    }
}
