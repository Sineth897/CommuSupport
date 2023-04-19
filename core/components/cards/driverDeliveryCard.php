<?php

namespace app\core\components\cards;

use app\models\deliveryModel;

class driverDeliveryCard
{
    private array $deliveryDetails = [];
    public function __construct()
    {
        $sql = "SELECT donorID,address FROM donor UNION SELECT doneeID,address FROM donee UNION SELECT ccID,address FROM communitycenter UNION SELECT subcategoryID,subcategoryName FROM subcategory";
        $stmt = deliveryModel::prepare($sql);
        $stmt->execute();
        $this->deliveryDetails = $stmt->fetchAll(\PDO::FETCH_KEY_PAIR);
    }

    public function showAssignedDeliveries(array $deliveries) : void {
        foreach ($deliveries as $delivery) {
            $this->showDeliveryCard($delivery);
        }
    }

    private function showDeliveryCard($data) : void {
        echo sprintf("<div class='driver-del-card' id='%s'>", $data['subdeliveryID'] . ',' . $data['type']);
        echo sprintf("<div class='card-column subcategory'><strong>Sub Category</strong><p>%s</p></div>", $this->deliveryDetails[$data['item']]);
        echo sprintf("<div class='card-column pickupaddress'><strong>Pick up Address</strong><p>%s</p></div>", $this->deliveryDetails[$data['start']]);
        echo sprintf("<div class='card-column deliveryaddress'><strong>Drop Off</strong><p>%s</p></div>",$this->deliveryDetails[$data['end']]);
        echo sprintf("<div class='card-column assigneddate'><strong>Created Date</strong><p>2023-01-45</p></div>", $data['createdDate']);
        echo "<div class='card-column route-complete-btns'>
            <a class='del-route' href=#'>Route</a>
            <a class='del-finish' href='#'>Finish</a>
        </div>";
        echo "<div class='card-column delivery-btns'>
            <a class='del-reassign' href='#'>Request to Re-Assign</a>
            </div></div>";

    }

}