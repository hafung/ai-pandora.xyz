<?php

namespace App\Services\AI\Factories;

use App\Services\AI\Contracts\AiAdapterInterface;
use Exception;
use App\Services\AI\Adapters\OpenAiAdapter;
use InvalidArgumentException;


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


    public function getAdapter(string $provider): AiAdapterInterface {
        $adapterClass = $this->resolveAdapterClass();
        $key = $this->generateKey($adapterClass, $provider);

        if (!isset($this->adapterInstances[$key])) {
            $this->adapterInstances[$key] = $this->createAdapter($adapterClass, $provider);
        }

        return $this->adapterInstances[$key];
    }

    private function resolveAdapterClass(): string {
        return OpenAiAdapter::class;
    }

    private function createAdapter(string $adapterClass, string $provider): AiAdapterInterface {
        if (!is_subclass_of($adapterClass, AiAdapterInterface::class)) {
            throw new InvalidArgumentException("$adapterClass must implement AiAdapterInterface");
        }

        switch ($adapterClass) {
            case OpenAiAdapter::class:
                return new OpenAiAdapter($provider);
            default:
                return new $adapterClass($provider);
        }
    }


    private function generateKey(string $adapterType, string $provider): string {
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
