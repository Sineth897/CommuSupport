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
        echo "<div class='mainDown' id='eventDisplay'>";
        if(!$events) {
            echo "<h1>There are no events to display</h1>";
        }
        else {
            foreach ($events as $event) {
                $this->eventCard($event);
            }
        }
        echo "</div>";
    }

    private function eventCard(array $event) {
        echo "<div class='eventCard' id='${event['eventID']}'>";
        echo "<div>";
        echo sprintf("<div><img src='%s' alt='Blood'></div>",$this->eventCategoryIcons[$event['eventCategory']]);
        echo "<div><img src='/CommuSupport/public/src/icons/event/participants.svg' alt='participants'>";
        echo sprintf("<p>%s</p></div>",$event['participationCount']);
        echo "</div>";
        echo sprintf("<div><h2>%s</h2></div>",$event['theme']);
        echo "<div>";
        echo "<div><img src='/CommuSupport/public/src/icons/event/location.svg' alt='location'>";
        echo sprintf("<p>%s</p> </div>",$event['location']);
        echo sprintf("<div><p>%s</p></div>",$event['date']);
        echo "</div></div>";
    }


}