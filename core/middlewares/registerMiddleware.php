<?php

namespace app\core\middlewares;

class registerMiddleware extends Middleware
{

    protected function accessRules(): array
    {
        return [
            'registerDriver' => [$this->MANAGER],
            'registerManager' => [$this->CHO],
            'registerLogistic' => [$this->CHO],
            'registerCho' => [$this->ADMIN],
        ];
    }
}