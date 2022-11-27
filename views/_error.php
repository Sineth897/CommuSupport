<?php
    /** @var $exception Exception */

    echo "<h3>". $exception->getMessage() . "</h3>";

    echo "<pre>";
    var_dump($_SESSION);
    echo "</pre>";
