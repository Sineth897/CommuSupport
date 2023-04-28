<?php

namespace app\core\components\cards;

use app\models\requestModel;

class requestcard
{

    private bool $accepted = false;
    private array $btns = [];
    private array $btnDetails = [
        "Accept" => "<button class='rq-btn btn-primary %s' value='%s'>Accept</button>",
        "Reject" => "<button class='rq-btn btn-danger %s' value='%s'>Reject</button>",
        "Approve" => "<button class='rq-btn btn-primary %s' value='%s'>Approve</button>",
        "View" => "<button class='rq-btn btn-primary %s' value='%s'>View</button>",
    ];

    public function __construct() {

    }

    public function displayRequests(array $requests = [],array $btns = [],bool $accepted = false): void
    {
        $this->accepted = $accepted;
        $this->btns = $btns;
        foreach ($requests as $request) {
            $this->requestCard($request);
        }
    }

    private function requestCard(array $request): void
    {
        echo sprintf("<div class='rq-card' id='%s'>",$this->accepted ? $request['acceptedID'] : $request['requestID']);
        echo "<div class='rq-card-header'>";
        echo sprintf("<h1>%s</h1>",$request['subcategoryName']);
        if($this->accepted) {
            echo "<div class='rq-delivery-status'>";
            echo sprintf("<strong>Delivery : </strong><p>%s</p>",$request['deliveryStatus']);
            echo "</div>";
        }
        echo "</div>";
        echo "<div class='rq-category'>";
        echo sprintf("<p>%s</p>",$request['categoryName']);
        echo "</div>";
        echo "<div class='rq-description'>";
        echo sprintf("<p>%s</p>",$request['notes']);
        echo "</div>";
        $this->displayBtns($request);
        if($this->accepted) {
            echo "<p class='rq-accepted-date'>";
            echo sprintf("<strong>Accepted On : </strong> %s",$request['acceptedDate']);
            echo "</p>";
        }
        echo "</div>";

    }

    private function displayBtns(array $request): void {
        echo "<div class='rq-btns'>";
        foreach ($this->btns as $btn) {
            echo sprintf($this->btnDetails[$btn[0]],$btn[1],$request['requestID']);
        }
        echo "</div>";
    }

}