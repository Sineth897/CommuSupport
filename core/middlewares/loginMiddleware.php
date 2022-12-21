<?php

namespace app\core\middlewares;

class loginMiddleware extends Middleware
{

    public function accessRules(): array
    {
        return [
            'employeeLogin' => [$this->GUEST, $this->DRIVER, $this->LOGISTIC, $this->MANAGER, $this->CHO, $this->ADMIN],
            'userLogin' => [$this->GUEST, $this->DONEE, $this->DONOR, $this->DRIVER, $this->LOGISTIC, $this->MANAGER, $this->CHO, $this->ADMIN],
            'logout' => [$this->GUEST, $this->DONEE, $this->DONOR, $this->DRIVER, $this->LOGISTIC, $this->MANAGER, $this->CHO, $this->ADMIN],
            'lockedAccount' => [$this->GUEST]
        ];
    }

}