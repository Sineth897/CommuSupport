<?php

namespace app\core\middlewares;

class deliveryMiddleware extends Middleware
{

    protected function accessRules(): array
    {
        return [
            'viewDeliveries' => [$this->MANAGER, $this->ADMIN,$this->LOGISTIC],
        ];
    }
}