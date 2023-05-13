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
        echo sprintf("<div class='cc-map'><div  id='%s' style='%s'></div></div>", $cc['ccID'],"width: 100%; height: 300px;");
        echo "<div class='cc-details'>";
        echo sprintf("<p class='cc-location-info'><span>Address</span><span>%s</span></p>", $cc['address']);
        echo sprintf("<p class='cc-location-info'><span>District</span><span id='%s' class='cho'>%s</span></p>",$cc['cho'] ,$this->chos[$cc['cho']]);
        echo "<p class='details-group'>";
        echo sprintf("<span>Contact</span><span>%s</span>", $cc['contactNumber']);
        echo "</p>";
        echo sprintf("<p class='details-group'>
                <span>Fax</span><span>%s </span>
            </p>",$cc['fax']);
        echo sprintf("<p class='details-group'>
                <span>Email</span><span>%s</span><a href='mailto:%s'><i class='material-icons'>email</i></a></p>
            </p>",$cc['email'],$cc['email']);
        echo '</div>';
        echo "</div>";
    }
}
