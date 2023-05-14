<?php

namespace app\core\middlewares;

class profileMiddleware extends Middleware
{

    protected function accessRules(): array
    {
        return
            [
                'doneeProfile' => [$this->DONEE],
                'donorProfile' => [$this->DONOR],
                'logisticProfile' => [$this->LOGISTIC],
                'managerProfile' => [$this->MANAGER],
                'driverProfile' => [$this->DRIVER],
                'choProfile' => [$this->CHO],
                'adminProfile' => [$this->ADMIN],
                'updateProfile' => [$this->DONEE, $this->DONOR, $this->LOGISTIC, $this->MANAGER, $this->DRIVER, $this->CHO],
            ];
    }






}