<?php

namespace app\core\middlewares;

class loginMiddleware extends Middleware
{

    public function accessRules(): array
    {
        return [
            'managerLogin' => [$this->MANAGER],
            'employeeLogin' => [$this->GUEST, $this->DONEE, $this->DONOR, $this->DRIVER, $this->LOGISTIC, $this->MANAGER, $this->CHO, $this->ADMIN],
            'userLogin' => [$this->GUEST, $this->DONEE, $this->DONOR, $this->DRIVER, $this->LOGISTIC, $this->MANAGER, $this->CHO, $this->ADMIN],

        ];
    }

}