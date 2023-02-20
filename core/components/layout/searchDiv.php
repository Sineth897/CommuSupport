<?php

namespace app\core\components\layout;

class searchDiv
{
    public function __construct()
    {
        echo "<div class='search-filter'>";
    }

    public function filterDivStart(): void
    {
        echo "<div class='filters' >";
    }

    public function filterDivEnd(): void
    {
        echo "</div>";
    }

    public function filterBegin(): void
    {
        echo "<div class='filter'>";
        echo "<p id='filter'><i class='material-icons'>filter_list</i><span>Filter</span></p>";
        echo "<div class='filter-box' id='filterOptions' style='display: none'>";
    }

    public function filterEnd(): void
    {
        echo "<button type='button' id='filterBtn' class='btn-cta-primary'>Filter</button>";
        echo "</div></div>";
    }

    public function sortBegin(): void
    {
        echo "<div class='sort'>";
        echo "<p id='sort'><i class='material-icons'>sort</i> <span>Sort</span></p>";
        echo "<div class='filter-box' id='sortOptions' style='display: none'>";
    }

    public function sortEnd(): void
    {
        echo "<button type='button' id='sortBtn' class='btn-cta-primary'>Sort</button>";
        echo "</div></div>";
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