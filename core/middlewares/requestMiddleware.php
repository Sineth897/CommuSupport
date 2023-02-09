<?php

namespace app\core\middlewares;

class requestMiddleware extends Middleware
{

    protected function accessRules(): array
    {
        return [
            'viewRequests' => [$this->ADMIN,$this->CHO,$this->MANAGER,$this->DONOR]
        ];
    }
}