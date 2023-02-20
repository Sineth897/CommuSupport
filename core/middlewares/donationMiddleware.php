<?php

namespace app\core\middlewares;

class donationMiddleware extends Middleware
{

    protected function accessRules(): array
    {
        return [
            'viewDonations' => [$this->ADMIN,$this->CHO,$this->MANAGER,$this->DONOR,$this->LOGISTIC],
            'createDonation' => [$this->DONOR],
        ];
    }
}