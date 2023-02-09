<?php

namespace app\core\middlewares;

class choMiddleware extends  Middleware
{

    protected function accessRules(): array
    {
        return [
            'viewCho' => [$this->ADMIN],
        ];
    }
}