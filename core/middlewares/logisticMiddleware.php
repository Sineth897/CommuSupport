<?php

namespace app\core\middlewares;

class logisticMiddleware extends Middleware
{
    protected function accessRules(): array
    {
        return[
          'viewLogistics'=>[$this->ADMIN,$this->CHO],
        ];
    }

    protected function accessRules(): array
    {
        return [
            'viewLogistics' => [$this->ADMIN,$this->CHO]
        ];
    }
}