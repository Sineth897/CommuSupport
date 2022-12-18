<?php

namespace app\core;

class Response
{
    private $data;

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

    public function setJsonData($data): void
    {
        $this->data = json_encode($data);
    }

    public function send(): void
    {
        echo $this->data;
    }


}
