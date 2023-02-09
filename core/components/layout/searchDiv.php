<?php

namespace app\core\components\layout;

class searchDiv
{
    public function __construct()
    {
        echo "<div class='search-filter'>";
    }

    public function filters(): void
    {
        echo "<div class='filters'>";
        $this->filter();
        $this->sort();
        echo "</div>";
    }

    public function filter(): void
    {
        echo "<div class='filter' id='sort'>";
        echo "<p><i class='material-icons'>filter_list</i><span>Filter</span></p>";
        echo "</div>";
    }

    public function sort(): void
    {
        echo "<div class='sort' id='sort'>";
        echo "<p><i class='material-icons'>sort</i> <span>Sort</span></p>";
        echo "</div>";
    }

    public function search(): void
    {
        echo "<div class='search'><input type='text' placeholder='Search' id='search'><a href='#'><i class='material-icons'>search</i></a></div>";
    }

    public function end(): void
    {
        echo "</div>";
    }

}