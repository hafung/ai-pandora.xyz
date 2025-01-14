<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class ApiException extends Exception {
    protected $code;
    protected $httpStatusCode;

    const CODE_VALIDATION_ERROR = 1001;
    const CODE_NOT_FOUND = 1002;
    const CODE_SERVER_ERROR = 1003;

    public function __construct($message = "An error occurred", $code = 400, $httpStatusCode = 200) {
        parent::__construct($message);
        $this->code = $code;
        $this->httpStatusCode = $httpStatusCode;
    }

    public function render($request) {
//        return new JsonResponse([
//            'error' => [
//                'message' => $this->getMessage(),
//                'code' => $this->code,
//            ]
//        ], $this->httpStatusCode);

        return new JsonResponse([
            'message' => $this->getMessage(),
            'code' => $this->code,
        ], $this->httpStatusCode);

    }
}
