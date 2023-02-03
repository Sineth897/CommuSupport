<?php

namespace app\core\middlewares;

class logisticMiddleware extends Middleware
{
    protected function accessRules(): array
    {
        return[
          'viewLogistic'=>[$this->ADMIN,$this->CHO],
        ];
    }

}