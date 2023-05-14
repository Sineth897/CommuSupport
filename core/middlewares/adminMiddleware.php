<?php

namespace app\core\middlewares;

class adminMiddleware extends Middleware
{

    protected function accessRules(): array
    {

        return [
            'addCategory' => [$this->ADMIN],
            'getCategories' => [$this->ADMIN],
            'addSubCategory' => [$this->ADMIN],
            'viewInventoryLog' => [$this->ADMIN],
            'requestPopup' => [$this->ADMIN],
            'getEventPopup' => [$this->ADMIN],
            'getDonationPopup' => [$this->ADMIN],
            'viewDriverStat' => [$this->ADMIN],
            'viewEventsStat' => [$this->ADMIN],
        ];

    }

}