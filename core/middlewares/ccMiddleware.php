<?php

namespace app\core\middlewares;

class ccMiddleware extends Middleware
{

    protected function accessRules(): array
    {
        return [
            'viewCC' => [$this->ADMIN,$this->CHO,$this->DONOR,$this->DONEE],
            'getCoordinates' => [$this->DONEE,$this->DONOR,$this->GUEST],
            'filterCC' => [$this->ADMIN],
            'viewIndividualCC' => [$this->ADMIN],
        ];
    }
}