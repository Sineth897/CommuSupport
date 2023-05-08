<?php

namespace app\core\middlewares;

class donorMiddleware extends Middleware
{

    protected function accessRules(): array
    {
        return [
            'viewDonors' => [$this->MANAGER, $this->ADMIN],
            'donorsFilterAdmin' => [$this->ADMIN],
            'filterDonors' => [$this->MANAGER],
            'donorPopup' => [$this->MANAGER]
        ];

    }
}
