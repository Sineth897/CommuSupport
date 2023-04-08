<?php

namespace app\core\components\cards;

class deliveryCard
{
    public function __construct()
    {
        // code
    }

    public function showDeliveryCard($data,$type = "") : void {
        foreach ($data as $item) {
            $this->showCard($item,$type);
        }
    }

    private function showCard($data,$type) : void {

        switch ($type) {
            case "directDonations":
                echo sprintf("<div class='delivery-card' id='%s'>",$data['donationID']);
                echo sprintf("<div class='delivery-card-header'><h4>%s</h4></div>", "Donation");
                break;
            case "acceptedRequests":
                echo sprintf("<div class='delivery-card' id='%s'>",$data['acceptedID']);
                echo sprintf("<div class='delivery-card-header'><h4>%s</h4></div>", "Accepted Request");

                break;
            case "ccDonations":
                echo sprintf("<div class='delivery-card' id='%s'>",$data['ccDonationID']);
                echo sprintf("<div class='delivery-card-header'><h4>%s</h4></div>", "CCDonation");

                break;
            default:
                return;
        }

        echo "<div class='map-div'>Map Here</div>";
        echo "<div class='card-content'>";
        echo sprintf("<div class='info-block'><span>Created:</span><span>%s</span></div>", $type == "acceptedRequests" ? $data['acceptedDate'] : $data['createdDate']);
        echo "<div class='info-block'><span>Distance</span><span>12.5 km</span></div>";
        echo sprintf("<div class='info-block'><span>Item</span><span>%s</span></div>",$data['subcategoryName']);
        echo sprintf("<div class='info-block'><a href='#' class='btn-primary' id='%s'>Assign</a></div>",'d3');
        echo "</div></div>";

    }


}