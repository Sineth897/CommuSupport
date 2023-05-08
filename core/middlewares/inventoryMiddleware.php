<?php

namespace app\core\middlewares;

class inventoryMiddleware extends Middleware
{

    protected function accessRules(): array
    {
        return [
            'viewInventory' => [$this->LOGISTIC,$this->ADMIN],
            'addInventory' => [$this->LOGISTIC],
            'filterInventory' => [$this->LOGISTIC,$this->ADMIN],
            'getCurrentInventory' => [$this->LOGISTIC,$this->ADMIN],
        ];
    }
}