<?php

namespace app\core\components\layout;

class headerDiv
{
    private array $pages = [
        'ongoing' => [
            'link' => '#',
            'icon' => 'cached',
            'name' => 'Ongoing'
        ],
        'pending' => [
            'link' => '#',
            'icon' => 'hourglass_empty',
            'name' => 'Pending'
        ],
        'completed' => [
            'link' => '#',
            'icon' => 'check_circle_outline',
            'name' => 'Completed'
        ],
        'cancelled' => [
            'link' => '#',
            'icon' => 'block',
            'name' => 'Cancelled'
        ],
        'individuals' => [
            'link' => '#',
            'icon' => 'person',
            'name' => 'Individuals'
        ],
        'organizations' => [
            'link' => '#',
            'icon' => 'business',
            'name' => 'Organizations'
        ],
    ];

    public function __construct()
    {
        echo "<div class='heading-pages'>";
    }

    public function heading($heading): void
    {
        echo sprintf("<div class='heading'><h1>%s</h1></div>", $heading);
    }

    public function pages($pages): void
    {
        echo "<div class='pages'>";
        foreach ($pages as $key) {
            $page = $this->pages[$key];
            echo sprintf("<a href='%s' id='%s'><i class='material-icons'>%s</i> %s</a>", $page['link'], $page['name'], $page['icon'], $page['name']);
        }
        echo "</div>";
    }

    public function end(): void
    {
        echo "</div>";
    }

}