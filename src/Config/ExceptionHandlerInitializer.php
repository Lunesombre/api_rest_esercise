<?php

namespace App\Config;

use Throwable;

class ExceptionHanlderInitializer
{
    public static function registerGlobalExceptionHandler()
    {
        set_exception_handler(function (Throwable $e) {
            http_response_code(500);
            echo json_encode([
                'error' => 'Une erreur est survenue.',
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
        });
    }
}
