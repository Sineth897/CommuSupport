<!--<link rel="stylesheet" href="./public/CSS/cards/request-card.css">-->
<link rel="stylesheet" href="./public/CSS/cards/cc-donation-card.css">
<!--<link rel="stylesheet" href="./public/CSS/cards/postedRequestCard.css">-->
<link rel="stylesheet" href="./public/CSS/cards/request-card.css">
<?php

$requestModel = new \app\models\requestModel();

$requests = $requestModel->getOwnRequests($_SESSION['user']);

?>


<style>




</style>


<div class="content">
    <div class="card-container">

        <?php

        $requestCards = new \app\core\components\cards\requestcard();

        $requestCards->displayRequests($requests['completedRequests'],[['View','viewAcceptedRequest']],true);

        echo "<pre>";
        print_r($requests['completedRequests']);
        echo "</pre>";

        ?>




        </div>
    </div>
</div>

<div class="rq-card" id="accepted64479cdfe7c7b8.">
    <div class="rq-card-header">
        <h1>Rice</h1>
        <div class="rq-delivery-status">
            <strong>Delivery : </strong><p>Completed</p>
        </div>
    </div>
    <div class="rq-category">
        <p>Food</p>
    </div>
    <div class="rq-description">
        <p>For the dependents of our organization</p>
    </div>
    <div class="rq-btns">
        <button class="rq-btn btn-primary viewActiveRequest" value="request63ff473a58aff3.5">View</button>
    </div>
    <p class="rq-accepted-date"><strong>2 users </strong> donated</p>
</div>


