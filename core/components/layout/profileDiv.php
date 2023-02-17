<?php

namespace app\core\components\layout;

use app\core\Application;

class profileDiv
{

    public function __construct()
    {
        echo "<div class='profile'>";
    }

    public function notification(): void
    {
        echo "<div class='notif-box'>";
        echo "<a href='#'><i class='material-icons'>notifications</i></a>";
        echo "</div>";
    }

    public function profile(): void
    {
        echo "<div class='profile-box'>";
        echo "<div class='name-box'>";
        echo sprintf("<h4>%s</h4>", $this->getUsername());
        echo sprintf("<p>%s</p>", $this->getPosition());
        echo "</div>";
        echo "<div class='profile-img'>";
        echo "<img src='https://www.w3schools.com/howto/img_avatar.png' alt='profile'>";
        echo "</div>";
        echo "</div>";
    }

    public function end(): void
    {
        echo "</div>";
    }

    private function getUsername(): string
    {
        return Application::session()->get('username');
    }

    private function getPosition(): string
    {
        switch(Application::session()->get('userType')){
            case 'donor':
                return 'Donor';
            case 'donee':
                return 'Donee';
            case 'manager':
                return 'Manager';
                case 'logistic':
                return 'Logistic';
            case 'cho':
                return 'CHO';
                case 'admin':
                return 'Admin';

            default:
                return 'User';
        }
    }
}