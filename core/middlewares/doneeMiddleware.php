<?php

namespace app\core\middlewares;

class doneeMiddleware extends Middleware
{

        protected function accessRules(): array
        {
            return [
                'viewDonees' => [$this->MANAGER, $this->ADMIN],
            ];

        }

}