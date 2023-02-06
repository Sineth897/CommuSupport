<?php

namespace app\core\middlewares;

class managerMiddleware extends Middleware
{
    protected function accessRules(): array
    {
        return[
            'viewManager'=>[$this->CHO,$this->ADMIN],
        ];
    }
}