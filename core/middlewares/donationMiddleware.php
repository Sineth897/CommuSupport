<?php

namespace app\core\middlewares;

class donationMiddleware extends Middleware
{

    protected function accessRules(): array
    {
        return [
            'viewDonations' => [$this->ADMIN,$this->CHO,$this->MANAGER,$this->DONOR,$this->LOGISTIC],
            'createDonation' => [$this->DONOR],
            'filterDonationsAdmin' => [$this->ADMIN],
            'filterDonationsEmployee' => [$this->MANAGER,$this->LOGISTIC],
            'donationPopupDonor' => [$this->DONOR,],
            'filterDonations' => [$this->DONOR],
            'donationPopupEmployee' => [$this->MANAGER,$this->LOGISTIC],
        ];
    }
}