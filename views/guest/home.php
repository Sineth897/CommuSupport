

<html >
<head>
    <title>Home</title>
    <link rel="stylesheet" href="public/CSS/styles.css" >
</head>
<body>
    <h1>Home</h1>
    <p>Home page</p>



</body>
</html>




<?php
    if( \app\core\Application::session()->getFlash('success') ) {
        echo \app\core\Application::session()->getFlash('success');
        var_dump($_SESSION);
    }
?>


