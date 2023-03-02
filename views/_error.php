<?php
    /** @var $exception Exception */


    echo "<h3>". $exception->getMessage() . "</h3>";
    echo "<h4>Path: " . $exception->getPath() . "</h4>";

    echo "<pre>";
    var_dump($_SESSION);
    var_dump($_REQUEST);
    echo "</pre>";
