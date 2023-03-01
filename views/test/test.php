<link rel="stylesheet" href="./public/CSS/flashMessages.css">
<?php

/** @var $request \app\core\Request */

echo '<pre>';
print_r($_SESSION['flashMessages']);
echo '</pre>';




//\app\core\Application::file()->getpdf('donee63ed83cd73a862.114back','donee' . DIRECTORY_SEPARATOR);

//echo \app\core\Application::file()->getFile('donee/front.pdf');

?>

<button id="btn" type="button">Click</button>

<!--<div class="content">-->
<!--    <iframe src="./src/donee/donee63ed83cd73a862.114back.pdf#view=fitH" title="Nic Back" width="100%" height="100%" style="overflow: hidden;pointer-events: none"></iframe>-->
<!---->
<!--</div>-->

<script>
    <?php if(!empty($_SESSION['flashMessages'])): ?>
    let flashMsgs = <?php echo json_encode($_SESSION['flashMessages']); ?>;
    <?php endif; ?>

</script>

<script type="module" src="./public/JS/flashMessages/flash.js"></script>

<script>

</script>