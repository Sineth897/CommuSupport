<?php

namespace app\core\middlewares;

class complaintMiddleware extends Middleware
{

    protected function accessRules(): array
    {
        return[
            'viewComplaints' =>[$this->ADMIN,$this->CHO,$this->DONOR,$this->DONEE]

        ];
    }
}