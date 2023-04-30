<?php

namespace app\core\components\cards;

use app\models\eventModel;

class eventcard
{
    private array $eventCategoryIcons = [];
    public function __construct() {
        $this->eventCategoryIcons = eventModel::getEventCategoryIcons();
    }

    public function displayEvents(array $events = [],string $id = "eventDisplay") {
        echo sprintf("<div class='card-container' id='%s'>",$id);
        if(!$events) {
            echo "<h1>There are no event to display</h1>";
        }
        else {
            foreach ($events as $event) {
                $this->eventCard($event);
            }
        }
        echo "</div>";
    }

    private function eventCard(array $event) {
        echo sprintf("<div class='event-card' id='%s'>",$event['eventID']);
        echo "<div class='event-card-header'>";
        echo "<img class='event-icon' src='{$this->eventCategoryIcons[$event['eventCategoryID']]}'>";
        echo "<div class='event-participants'><i class='material-icons'>people</i>";
        echo sprintf("<p class='participant-count'>%s</p></div>",$event['participationCount']);
        echo "</div>";
        echo sprintf("<div class='event-title'><h2>%s</h2></div>",$event['theme']);
        echo "<div class='event-details'>";
        echo "<div class='event-location'><img src='/CommuSupport/public/src/icons/event/location.svg'>";
        echo sprintf("<p>%s</p> </div>",$event['location']);
        echo sprintf("<p>%s</p>",$event['date']);
        echo "</div></div>";
    }




}