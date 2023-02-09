<?php

namespace app\core\middlewares;

class complaintMiddleware extends Middleware
{

    protected function accessRules(): array
    {
        return[
            'viewComplaint' =>[$this->ADMIN,$this->CHO,$this->DONOR,$this->DONEE]
        ];
    }
}