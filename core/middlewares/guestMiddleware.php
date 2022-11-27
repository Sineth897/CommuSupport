<?php

namespace app\core\middlewares;

use app\core\exceptions\forbiddenException;
use app\core\Response;

class guestMiddleware extends Middleware
{

    protected function accessRules(): array
    {
        return [
            'home' => [$this->GUEST, $this->DONEE, $this->DONOR, $this->DRIVER, $this->LOGISTIC, $this->MANAGER, $this->ADMIN],
            'login' => [$this->GUEST, $this->DONEE, $this->DONOR, $this->DRIVER, $this->LOGISTIC, $this->MANAGER, $this->ADMIN],
            'form' => [$this->MANAGER, $this->ADMIN]
        ];
    }
}
