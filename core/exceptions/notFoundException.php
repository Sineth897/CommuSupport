<?php

namespace app\core\exceptions;

class notFoundException extends \Exception
{
    protected $message = 'Page not found';
    protected $code = 404;

}