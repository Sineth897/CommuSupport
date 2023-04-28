<?php

namespace app\core\components\cards;

use app\models\choModel;

class CCcard
{
    private array $chos = [];

    public function __construct() {
        $this->chos = CHOModel::getCHOs();
    }

    public function displayCCs(array $ccs = []): void
    {
        foreach ($ccs as $cc) {
            $this->CCCard($cc);
        }

    }

    private function CCCard($cc): void {
        echo sprintf("<div class='cc-card' title=%s>", $cc['ccID']);
        echo "<div class='cc-card-header'>";
        echo sprintf("<h1>%s</h1>",$cc['city']);
        echo "</div>";
        echo sprintf("<div class='cc-map'><div  id='%s' style='%s'></div></div>", $cc['ccID'],"width: 100%; height: 15rem;");
        echo "<div class='cc-details'>";
        echo sprintf("<p class='cc-location-info'><strong>Address : </strong>%s</p>", $cc['address']);
        echo sprintf("<p class='cc-location-info'><strong>District : </strong><span id='%s' class='cho'>%s</span></p>",$cc['cho'] ,$this->chos[$cc['cho']]);
        echo "<div class='details-group'>";
        echo sprintf("<p><strong>Contact : </strong>%s</p>
                <div class='icon-button'><i class='material-icons'>call</i></div>", $cc['contactNumber']);
        echo "</div>";
        echo sprintf("<div class='details-group'>
                <p><strong>Fax : </strong>%s</p>
                <div class='icon-button'><i class='material-icons'>fax</i></div>
            </div>",$cc['fax']);
        echo sprintf("<div class='details-group'>
                <p><strong>Email : </strong>%s</p>
                <div class='icon-button'><i class='material-icons'>email</i></div>
            </div>",$cc['email']);
        echo '</div>';
        echo "</div>";
    }
}