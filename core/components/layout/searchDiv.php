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
        echo "<div class='filter' id='filter'>";
        echo "<p ><i class='material-icons'>filter_alt</i><span>Filter</span></p>";
        echo "<div class='filter-box' id='filterOptions' style='display: none'>";
    }

    public function filterEnd(): void
    {
        echo "<button type='button' id='filterBtn' class='btn-primary'>Filter</button>";
        echo "</div></div>";
    }

    public function sortBegin(): void
    {
        echo "<div class='sort' id='sort'>";
        echo "<p><i class='material-icons'>sort</i> <span>Sort</span></p>";
        echo "<div class='filter-box' id='sortOptions' style='display: none'>";
    }

    public function sortEnd(): void
    {
        echo "<button type='button' id='sortBtn' class='btn-primary'>Sort</button>";
        echo "</div></div>";
    }

    public function search(): void
    {
        echo "<div class='search'><input type='text' placeholder='Search' id='searchInput'><a href='#' id='searchBtn'><i class='material-icons'>search</i></a></div>";
    }

    public function end(): void
    {
        echo "</div>";
    }

}