<?php

namespace app\core\middlewares;

class profileMiddleware extends Middleware
{

    protected function accessRules(): array
    {
        return
            [
                'viewProfile' => [$this->MANAGER, $this->CHO, $this->ADMIN, $this->LOGISTIC, $this->DONEE, $this->DONOR, $this->DRIVER],
                'doneeProfile' => [$this->DONEE],
            ];
    }






}