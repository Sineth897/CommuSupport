<?php

namespace app\core\components\layout;

class headerDiv
{
    private array $pages = [
        'ongoing' => [
            'id' => 'ongoing',
            'link' => '#',
            'icon' => 'cached',
            'name' => 'Ongoing'
        ],
        'pending' => [
            'id' => 'pending',
            'link' => '#',
            'icon' => 'hourglass_empty',
            'name' => 'Pending'
        ],
        'completed' => [
            'id' => 'completed',
            'link' => '#',
            'icon' => 'check_circle_outline',
            'name' => 'Completed'
        ],
        'cancelled' => [
            'id' => 'cancelled',
            'link' => '#',
            'icon' => 'block',
            'name' => 'Cancelled'
        ],
        'individuals' => [
            'id' => 'individual',
            'link' => '#',
            'icon' => 'person',
            'name' => 'Individual'
        ],
        'organizations' => [
            'id' => 'organization',
            'link' => '#',
            'icon' => 'business',
            'name' => 'Organization'
        ],
        'published' => [
            'id' => 'published',
            'link' => '#',
            'icon' => 'publish',
            'name' => 'Published'
        ],
        'history' => [
            'id' => 'history',
            'link' => '#',
            'icon' => 'history',
            'name' => 'History'
        ],
        'active' => [
            'id' => 'active',
            'link' => '#',
            'icon' => 'broadcast_on_personal',
            'name' => 'Active'
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
            echo sprintf("<a href='%s' id='%s'><i class='material-icons'>%s</i> %s</a>", $page['link'], $page['id'], $page['icon'], $page['name']);
        }
        echo "</div>";
    }

    public function end(): void
    {
        echo "</div>";
    }

}