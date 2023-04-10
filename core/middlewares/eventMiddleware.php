<?php

namespace app\core\middlewares;

class eventMiddleware extends Middleware
{
    protected function accessRules(): array
    {
        return [
            'viewEvents' => [$this->MANAGER,$this->CHO,$this->ADMIN,$this->GUEST,$this->DONEE,$this->DONOR],
            'createEvent' => [$this->MANAGER],
            'filterEvents' => [$this->MANAGER,$this->CHO,$this->GUEST,$this->DONEE,$this->DONOR],
            'eventPopUp' => [$this->MANAGER,$this->CHO,$this->ADMIN,$this->GUEST,],
            'eventPopUpUser' => [$this->DONEE,$this->DONOR],
            'participate' => [$this->DONEE,$this->DONOR],
            'filterEventsUser' => [$this->DONEE,$this->DONOR],
            'updateEvent' => [$this->MANAGER],
            'filterEventsAdmin' => [$this->ADMIN],
        ];
    }
}