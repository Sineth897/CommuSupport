<?php

namespace app\core\middlewares;

class driverMiddleware extends Middleware
{
    protected function accessRules(): array
    {
        return [
            'viewDrivers' => [$this->MANAGER, $this->ADMIN,$this->LOGISTIC],
        ];

    }
}