<?php

namespace app\core\components\cards;

use app\models\requestModel;

class requestcard
{

    private array $btns = [];
    private array $btnDetails = [
        "Accept" => "<button class='rq-btn btn-primary %s' value='%s'>Accept</button>",
        "Reject" => "<button class='rq-btn btn-danger %s' value='%s'>Reject</button>",
        "Approve" => "<button class='rq-btn btn-primary %s' value='%s'>Approve</button>",
        "View" => "<button class='rq-btn btn-primary %s' value='%s'>View</button>",
    ];

    public function __construct() {

    }

    public function displayRequests(array $requests = [],array $btns = []): void
    {
        $this->btns = $btns;
        foreach ($requests as $request) {
            $this->requestCard($request);
        }
    }

    private function requestCard(array $request): void
    {
        echo sprintf("<div class='rq-card' id='%s'>",$request['requestID']);
        echo "<div class='rq-card-header'>";
        echo sprintf("<h1>%s</h1>",$request['subcategoryName']);
        echo "</div>";
        echo "<div class='rq-category'>";
        echo sprintf("<p>%s</p>",$request['categoryName']);
        echo "</div>";
        echo "<div class='rq-description'>";
        echo sprintf("<p>%s</p>",$request['notes']);
        echo "</div>";
        $this->displayBtns($request);
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