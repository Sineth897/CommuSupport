<?php

namespace app\core;

class Response
{
    public static function staticRedirect(string $URL) {
        (new static())->redirect($URL);
    }

    public function setStatusCode(int $code): void
    {
        http_response_code($code);
    }

    public function redirect(string $URL): void
    {
        header("Location: /CommuSupport".$URL);
    }


}
