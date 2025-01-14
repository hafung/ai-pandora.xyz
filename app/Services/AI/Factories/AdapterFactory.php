<?php

namespace App\Services\AI\Factories;

use InvalidArgumentException;
use App\Services\AI\Adapters\OpenAiAdapter;
use App\Services\AI\Adapters\SiliconAiAdapter;
use App\Services\AI\Contracts\TextAiServiceInterface;
use App\Services\AI\Contracts\MusicAiServiceInterface;

class AdapterFactory {

    protected static array $instances = [];

    private function __construct() {
    }

    protected static function getAdapter(string $class, array $params = []) {
        // 生成唯一的实例标识符
        $instanceKey = $class . ':' . md5(json_encode($params));

        if (!isset(self::$instances[$instanceKey])) {
            self::$instances[$instanceKey] = new $class(...$params);
        }

        return self::$instances[$instanceKey];
    }


    /**
     * Create an adapter instance.
     * $openAiAdapter = AdapterFactory::create(OpenAiAdapter::class, [$provider]);
     * @param string $adapter The name of the adapter.
     * @param array $params
     * @return MusicAiServiceInterface|TextAiServiceInterface|OpenAiAdapter|SiliconAiAdapter
     */
    public static function create(string $adapter, array $params = []) {
        if (!class_exists($adapter)) {
            throw new InvalidArgumentException("Unknown adapter: $adapter");
        }

        return self::getAdapter($adapter, $params);
    }

}
