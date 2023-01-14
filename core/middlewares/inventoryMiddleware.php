<?php

namespace app\core\middlewares;

class inventoryMiddleware extends Middleware
{

    protected function accessRules(): array
    {
        return [
            'viewInventory' => [$this->LOGISTIC,$this->ADMIN],
        ];
    }
}