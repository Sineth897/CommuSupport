<?php

namespace app\core\middlewares;

class employeeMiddleware extends Middleware
{

    protected function accessRules(): array
    {
        return [
            'viewEmployees' => [$this->ADMIN],
            'filterEmployees' => [$this->ADMIN],
            'employeesPopup' => [$this->ADMIN],
        ];
    }
}