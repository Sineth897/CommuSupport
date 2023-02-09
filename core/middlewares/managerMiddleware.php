<?php

namespace app\core\middlewares;

class managerMiddleware extends Middleware
{
    protected function accessRules(): array
    {
        return[
            'viewManagers'=>[$this->CHO,$this->ADMIN],
        ];
    }
}