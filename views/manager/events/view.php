<?php

/** @var $model \app\models\eventModel */

use app\core\Application;

$managerID = Application::$app->session->get('user');
$manager = new \app\models\managerModel();
$manager = $manager->findOne(['employeeID' => $managerID]);
$ccID = $manager->ccID;

$events = $model->retrieve(["ccID" => $ccID]);

if( empty($events) ) {
    echo "No events";
} else {
    echo "<pre>";
    foreach ($events as $event) {
        print_r($event);
    }
    echo "</pre>";
}



?>



<button type="button" id="filterBtn">Filter</button>



<?php $creatEvent = \app\core\components\form\form::begin('./events/create', 'get'); ?>

<button> Create event </button>

<?php $creatEvent->end(); ?>

<?php $logout = \app\core\components\form\form::begin('../logout', 'post'); ?>

<button> logout </button>

<?php $logout->end(); ?>

<script type="module" src="../public/JS/manager/events/view.js"></script>
