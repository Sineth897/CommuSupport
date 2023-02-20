<?php

namespace app\core\components\tables;

class table
{
    private array $tableHeaders = [];
    private array $arrayKeys = [];

    public function __construct(array $tableHeaders = [], array $arrayKeys = []) {
        $this->tableHeaders = $tableHeaders;
        $this->arrayKeys = $arrayKeys;
    }

    public function displayTable(array $tableData = []) {
        echo "<table>";
        $this->displayTableHeaders();
        $this->displayTableData($tableData);
        echo "</table>";
    }

    private function displayTableHeaders() {
        echo "<thead><tr>";
        foreach ($this->tableHeaders as $tableHeader) {
            echo sprintf("<th>%s</th>",$tableHeader);
        }
        echo "</tr></thead>";
    }

    private function displayTableData(array $tableData) {
        foreach ($tableData as $data) {
            echo "<tr>";
            foreach ($this->arrayKeys as $key)   {
                echo sprintf("<td>%s</td>", $data[$key]);
            }
            echo "</tr>";
        }
    }
}