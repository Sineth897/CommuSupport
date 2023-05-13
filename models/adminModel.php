<?php

namespace app\models;

use app\core\DbModel;

class adminModel extends DbModel
{

    public function table(): string
    {
        return '';
    }

    public function attributes(): array
    {
        return [];
    }

    public function primaryKey(): string
    {
        return '';
    }

    public function rules(): array
    {
        return [];
    }

    /**
     * @return array
     */
    public function getAdminInformationForProfile() : array {
        return [
            $this->getPersonalInfo()[0],
            $this->getAdminStatistics(),
        ];
    }

    /**
     * @return array
     */
    private function getPersonalInfo() : array {

        return [
            ['username' => 'RootAdmin']
        ];
    }

    /**
     * @return array
     */
    private function getAdminStatistics() : array {

        $arrayOfSql = [
            $sqlDonees = "SELECT 'Donees Registered This Month',COUNT(*) FROM donee d
                                            WHERE MONTH(d.registeredDate) = MONTH(CURRENT_DATE()) AND YEAR(d.registeredDate) = YEAR(CURRENT_DATE())",

            $sqlDonors = "SELECT 'Donors Registered This Month',COUNT(*) FROM donor d
                                            WHERE MONTH(d.registeredDate) = MONTH(CURRENT_DATE()) AND YEAR(d.registeredDate) = YEAR(CURRENT_DATE())",

            $sqlRequestsPosted = "SELECT 'Requests This Month',COUNT(*) FROM request r
                                            WHERE MONTH(r.postedDate) = MONTH(CURRENT_DATE()) AND YEAR(r.postedDate) = YEAR(CURRENT_DATE())",

            $sqlDonations = "SELECT 'Donations received This Month',COUNT(*) FROM donation d
                                            WHERE MONTH(d.date) = MONTH(CURRENT_DATE()) AND YEAR(d.date) = YEAR(CURRENT_DATE())",

            $sqlEvents = "SELECT 'Events This Month',COUNT(*) FROM event e
                                            WHERE MONTH(e.date) = MONTH(CURRENT_DATE()) AND YEAR(e.date) = YEAR(CURRENT_DATE())",

            $sqlComplaints = "SELECT 'Complaints Filed This Month',COUNT(*) FROM complaint c
                                            WHERE MONTH(c.filedDate) = MONTH(CURRENT_DATE()) AND YEAR(c.filedDate) = YEAR(CURRENT_DATE())",
        ];

        $statement = self::prepare(implode(" UNION ",$arrayOfSql));
        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_KEY_PAIR);
    }
}