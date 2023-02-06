<?php

namespace app\core\middlewares;

class acceptedMiddleware extends Middleware
{

    protected function accessRules(): array
    {
        return [
            'viewAcceptedRequests' => [$this->ADMIN,$this->CHO,$this->DONOR]
        ];
    }
}