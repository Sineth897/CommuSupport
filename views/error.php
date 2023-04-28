<?php
/** @var $exception Exception */
//
//echo "<h1>Something went wrong</h1>";

//echo "<h3>". $exception->getMessage() . "</h3>";
//echo "<pre>";
//var_dump($_SESSION);
//var_dump($_REQUEST);
//echo "</pre>";
?>
<div class="error_code">
<img id ="error_code" src="/CommuSupport/public/src/errors/<?php echo $exception->getCode()?>.svg" alt="">
</div>

