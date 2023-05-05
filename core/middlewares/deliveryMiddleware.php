<?php

namespace app\core\middlewares;

class deliveryMiddleware extends Middleware
{

    protected function accessRules(): array
    {
        return [
            'viewDeliveries' => [$this->MANAGER, $this->ADMIN,$this->LOGISTIC,$this->DRIVER],
            'createDelivery'=> [$this->LOGISTIC],
            'deliveryPopup'=> [$this->LOGISTIC],
            'assignDriver'=> [$this->LOGISTIC],
            'completedDeliveries'=> [$this->DRIVER],
            'getRouteDetails'=> [$this->DRIVER],
            'completeDelivery'=> [$this->DRIVER],
            'requestToReassign' => [$this->DRIVER],
            'filterDeliveries' =>   [$this->LOGISTIC],
            'deliveryPopupDriver' => [$this->DRIVER],
        ];
    }
}