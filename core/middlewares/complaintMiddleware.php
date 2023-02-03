<?php

namespace app\core\middlewares;

class complaintMiddleware extends Middleware
{

<<<<<<< HEAD

    protected function accessRules(): array
    {
        return[

            'viewComplaints'=>[$this->ADMIN,$this->CHO],

=======
    protected function accessRules(): array
    {
        return[
            'viewComplaint' =>[$this->ADMIN,$this->CHO,$this->DONOR,$this->DONEE]
>>>>>>> eff85e44986d3bc01499410308913423699b159b
        ];
    }
}