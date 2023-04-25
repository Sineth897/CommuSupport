<?php

namespace app\core\components\cards;

use app\models\eventModel;

class eventcard
{
    private array $eventCategoryIcons = [];
    public function __construct() {
        $this->eventCategoryIcons = eventModel::getEventCategoryIcons();
    }

    public function displayEvents(array $events = []) {
        echo "<div class='card-container' id='eventDisplay'>";
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
        echo sprintf("<img src='%s' alt='Blood' class='event-icon'>",$this->eventCategoryIcons[$event['eventCategoryID']]);
        echo "<div class='event-participants'><i class='material-icons'>people</i>";
        echo sprintf("<p class='participant-count'>%s</p></div>",$event['participationCount']);
        echo "</div>";
        echo sprintf("<div class='event-title'><h3>%s</h3></div>",$event['theme']);
        echo "<div class='event-details'>";
        echo "<div class='event-location'><i class='material-icons'>location_on</i>";
        echo sprintf("<p>%s</p> </div>",$event['location']);
        echo sprintf("<p>%s</p>",$event['date']);
        echo "</div></div>";
    }


}