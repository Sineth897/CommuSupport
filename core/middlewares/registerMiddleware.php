<?php

namespace app\core\middlewares;

class registerMiddleware extends Middleware
{

    protected function accessRules(): array
    {
        return [
            'registerDriver' => [$this->MANAGER],
            'registerManager' => [$this->ADMIN],
            'registerLogistic' => [$this->ADMIN],
        ];
    }
}