<?php

?>

<?php
echo $_SESSION=['user'];
echo "<pre>";
if(empty(\app\core\Application::session()->getFlash('success'))){
    print_r(\app\core\Application::session()->getFlash('error'));
}else if (empty(\app\core\Application::session()->getFlash('error'))){
    print_r(\app\core\Application::session()->getFlash('success'));
}
