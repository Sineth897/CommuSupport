<?php

namespace app\core\components\cards;

class deliveryCard
{
    public function __construct()
    {
        // code
    }

    public function showDeliveryCard($data, $type = ""): void
    {
        foreach ($data as $item) {
            $this->showCard($item, $type);
        }
    }

    private function showCard($data, $type): void
    {

        switch ($type) {
            case "directDonations":
                echo sprintf("<div class='delivery-card' id='%s'>", $data['donationID']);
                echo sprintf("<div class='delivery-card-type'><h4>%s</h4></div>", "Direct Donation");
                echo sprintf("<div class='delivery-card-header'><h4>%s</h4><p class='log-del-status-cancelled'>Delivery Status</p></div>", $data['subcategoryName']);
//                var_dump($data);
                echo "<div class='log-del-details'>";
                $addressParts = explode(",", $data['address']);
                $city = trim(end($addressParts));
                echo sprintf("<p><strong>Start: </strong>%s</p>", $city);
                echo "<p><strong>Dest: </strong>(CC Name) CC</p>";
                echo sprintf("<p><strong>Created: </strong>%s</p>", $data['date']);
                break;

            case "acceptedRequests":
                echo sprintf("<div class='delivery-card' id='%s'>", $data['acceptedID']);
                echo sprintf("<div class='delivery-card-type'><h4>%s</h4></div>", "Accepted Request");
                echo sprintf("<div class='delivery-card-header'><h4>%s</h4><p class='log-del-status-cancelled'>Delivery Status</p></div>", $data['subcategoryName']);
//                var_dump($data);
                echo "<div class='log-del-details'>";
                $addressParts = explode(",", $data['address']);
                $city = trim(end($addressParts));
                echo sprintf("<p><strong>Start: </strong>%s</p>", $city);
                echo "<p><strong>Dest: </strong>(CC Name) CC</p>";
                echo sprintf("<p><strong>Created: </strong>%s</p>", $data['approvedDate']);
                break;

            case "ccDonations":
                echo sprintf("<div class='delivery-card' id='%s'>", $data['ccDonationID']);
                echo sprintf("<div class='delivery-card-type'><h4>%s</h4></div>", "CCDonation");
                echo sprintf("<div class='delivery-card-header'><h4>%s</h4><p class='log-del-status-cancelled'>Delivery Status</p></div>", $data['subcategoryName']);
                echo "<div class='log-del-details'>";
                echo "<p><strong>Start: </strong>(CC Name) CC</p>";
                echo "<p><strong>Dest: </strong>(CC Name) CC</p>";
                echo sprintf("<p><strong>Created: </strong>%s</p>", $data['date']);
                break;

            default:
                return;
        }


        echo "</div>";
        echo "<div class='log-del-btns'>";
        echo "<button class='log-del-primary'>More Details</button>";
        echo "<button class='log-del-primary'><i class='material-icons'>location_on</i>Route</button>
        </div></div>";

    }


}

