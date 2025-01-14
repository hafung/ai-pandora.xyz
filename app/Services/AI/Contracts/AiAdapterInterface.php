<?php

namespace App\Services\AI\Contracts;

use App\Services\AI\Params\AiChatParams;

interface AiAdapterInterface
{
    public function chat(AiChatParams $params, bool $stream = false, callable $callback = null): ?string;

    public function getError();
}
