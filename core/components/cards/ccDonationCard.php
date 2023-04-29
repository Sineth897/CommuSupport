<?php

namespace app\core\components\cards;

class ccDonationCard
{

    public function __construct()
    {
    }

    public function showCCDonationCards($CCDonations) {
        foreach ($CCDonations as $CCDonation) {
            $this->showCCDonationCard($CCDonation);
        }
    }

    private function showCCDonationCard($CCDonation) {
        echo sprintf('<div class="CC-donation-card" id="%s">',$CCDonation['ccDonationID']);
        echo sprintf('<div class="CC-donation-header">');
        echo sprintf('<h4> %s </h4>',$CCDonation['subcategoryName']);
        echo sprintf('</div>');
        echo sprintf('<div class="CC-donation-details">');
        echo sprintf('<p> <span> Amount: </span> %s </p>',$CCDonation['amount']);
        echo sprintf('<p> <span> Posted By: </span> %s  </p>',$CCDonation['city']);
        echo sprintf('</div>');
        echo sprintf("<div class='CC-donation-btns'><button class='CC-donation-primary accept'>Accept</button></div>");
        echo "</div>";
    }

}