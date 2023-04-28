<?php

namespace app\core\middlewares;

class driverMiddleware extends Middleware
{
    protected function accessRules(): array
    {
        return [
            'viewDrivers' => [$this->MANAGER, $this->ADMIN,$this->LOGISTIC],
            'filterDriversAdmin' => [$this->ADMIN],
            'filterDrivers' => [$this->MANAGER,$this->LOGISTIC],
            'driverPopup' => [$this->MANAGER, $this->ADMIN,$this->LOGISTIC],
        ];

    }
}