<?php

namespace app\models;

use app\core\DbModel;

class deliveryModel extends DbModel
{

    public string $deliveryID = '';
    public string $deliveredBy = '';
    public string $createdDate = '';
    public string $status = '';
    public int $subdeliveryCount = 0;
    public string $start = '';
    public string $end = '';
    public string $completedDate = '';
    public string $completedTime = '';


    /**
     * @return array
     */
    public function rules(): array
    {
        return [

        ];
    }

    /**
     * @return string
     */
    public function table(): string
    {
        return 'delivery';
    }

    /**
     * @return string[]
     */
    public function attributes(): array
    {
        return ['deliveryID', 'subdeliveryCount', 'start', 'end',];
    }

    /**
     * @return string
     */
    public function primaryKey(): string
    {
        return 'deliveryID';
    }

    /**
     * @param string $driverID
     * @return array
     */
    public function getAssignedDeliveries(string $driverID) : array {

        // queries to get deliveries related to each process
        $cols = "s.subdeliveryID,s.start,s.end,s.createdDate,t.item,s.status";
        $sql1 = "SELECT $cols,'acceptedRequest' AS type from subdelivery s INNER JOIN acceptedrequest t on s.deliveryID = t.deliveryID WHERE s.deliveredBy = '$driverID' AND s.status IN ('Ongoing','Reassign Requested')";
        $sql2 = "SELECT $cols,'donation' AS type from subdelivery s INNER JOIN donation t on s.deliveryID = t.deliveryID WHERE s.deliveredBy = '$driverID' AND s.status IN ('Ongoing','Reassign Requested')";
        $sql3 = "SELECT $cols,'ccdonation' AS type from subdelivery s INNER JOIN ccdonation t on s.deliveryID = t.deliveryID WHERE s.deliveredBy = '$driverID' AND s.status IN ('Ongoing','Reassign Requested')";

        // take union of deliveries related to all processes
        $stmt1 = self::prepare($sql1 . " UNION " . $sql2 . " UNION " . $sql3);
        $stmt1->execute();
        return $stmt1->fetchAll(\PDO::FETCH_ASSOC);

    }

    /**
     * @param string $driverID
     * @param array $filters
     * @param array $sort
     * @return array
     */
    public static function getAssignedDeliveriesFilteredAndSorted(string $driverID, array $filters, array $sort) : array {

        // initialize empty string to store where and order by clauses
        $where = '';

        // if filter variables are available then add them to where clause
        if(!empty($filters)) {
            $where = " WHERE s.item = '{$filters['category']}' ";
        }

        // if sort variables are available then add them to order by clause
        if(!empty($sort["DESC"])) {
            $where .= ' ORDER BY ' . implode(',', $sort["DESC"]) . ' DESC ';
        }

        // queries to get deliveries related to each process
        $cols = "s.subdeliveryID,s.start,s.end,s.createdDate,t.item,s.status";
        $sql1 = "SELECT $cols,'acceptedRequest' AS type from subdelivery s INNER JOIN acceptedrequest t on s.deliveryID = t.deliveryID WHERE s.deliveredBy = '$driverID' AND s.status IN ('Ongoing','Reassign Requested')";
        $sql2 = "SELECT $cols,'donation' AS type from subdelivery s INNER JOIN donation t on s.deliveryID = t.deliveryID WHERE s.deliveredBy = '$driverID' AND s.status IN ('Ongoing','Reassign Requested')";
        $sql3 = "SELECT $cols,'ccdonation' AS type from subdelivery s INNER JOIN ccdonation t on s.deliveryID = t.deliveryID WHERE s.deliveredBy = '$driverID' AND s.status IN ('Ongoing','Reassign Requested')";

        // take union of deliveries related to all processes
        $sql = $sql1 . " UNION " . $sql2 . " UNION " . $sql3;

        // selects from the result of the select statement
        $stmt1 = self::prepare("SELECT * FROM ($sql) AS s $where");
        $stmt1->execute();
//        return $stmt1->fetchAll(\PDO::FETCH_ASSOC);
        return $stmt1->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * @param string $deliveryID
     * @param string $completed
     * @return void
     */
    public static function updateDeliveryAsCompleted(string $deliveryID, string $completed) : void {
        $sql = "UPDATE delivery SET status = 'Completed', completedDate =:completedDate, completedTime = :completedTime WHERE deliveryID = :deliveryID";
        $stmt = self::prepare($sql);
        $stmt->bindValue(':deliveryID', $deliveryID);
        $stmt->bindValue(':completedDate', $completed);
        $stmt->bindValue(':completedTime', $completed);
        $stmt->execute();
    }

    /**
     * @param string $ccDonationID
     * @return array
     */
    public static function getDeliveryInfoOfCCDonation(string $ccDonationID) : array {
        $sql = "SELECT s.*,d.*,s.status AS deliveryStatus FROM subdelivery s LEFT JOIN delivery d ON s.deliveryID = d.deliveryID LEFT JOIN ccdonation cc ON s.deliveryID = cc.deliveryID WHERE cc.ccdonationID = '$ccDonationID'";
        $stmt = self::prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getCompletedDeliveriesByDriverID(string $employeeID) : array {

        // queries to get deliveries related to each process
        $cols = "s.subdeliveryID,s.start,s.end,s.createdDate,t.item,s.status,s.completedDate,s.completedTime";
        $sql1 = "SELECT $cols,'acceptedRequest' AS type from subdelivery s INNER JOIN acceptedrequest t on s.deliveryID = t.deliveryID WHERE s.deliveredBy = '$employeeID' AND s.status = 'Completed'";
        $sql2 = "SELECT $cols,'donation' AS type from subdelivery s INNER JOIN donation t on s.deliveryID = t.deliveryID WHERE s.deliveredBy = '$employeeID' AND s.status = 'Completed'";
        $sql3 = "SELECT $cols,'ccdonation' AS type from subdelivery s INNER JOIN ccdonation t on s.deliveryID = t.deliveryID WHERE s.deliveredBy = '$employeeID' AND s.status = 'Completed'";

        // take union of deliveries related to all processes
        $stmt1 = self::prepare($sql1 . " UNION " . $sql2 . " UNION " . $sql3);
        $stmt1->execute();
        return $stmt1->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * @return array
     */
    public static function getDestinationAddresses() : array {
        $sql = "SELECT donorID,address FROM donor UNION SELECT doneeID,address FROM donee UNION SELECT ccID,address FROM communitycenter UNION SELECT subcategoryID,subcategoryName FROM subcategory";
        $stmt = self::prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_KEY_PAIR);
    }

    /**
     * @param string $employeeID
     * @param array $filter
     * @param array $sort
     * @return array
     */
    public static function getCompletedDeliveriesByDriverIDFilteredAndSorted(string $employeeID, array $filter, array $sort) : array {

        // initialize empty string to store where and order by clauses
        $where = '';

        // if filter variables are available then add them to where clause
        if(!empty($filter)) {
            $where = " WHERE s.item = '{$filter['category']}' ";
        }

        // if sort variables are available then add them to order by clause
        if(!empty($sort["DESC"])) {
            $where .= ' ORDER BY ' . implode(',', $sort["DESC"]) . ' DESC ';
        }


        // queries to get deliveries related to each process
        $cols = "s.subdeliveryID,s.distance,s.start,s.end,s.createdDate,t.item,s.status,s.completedDate,s.completedTime";

        $sql1 = "SELECT $cols,'acceptedRequest' AS type from subdelivery s 
                                        INNER JOIN acceptedrequest t on s.deliveryID = t.deliveryID 
                                        WHERE s.deliveredBy = '$employeeID' AND s.status = 'Completed'";

        $sql2 = "SELECT $cols,'donation' AS type from subdelivery s 
                                        INNER JOIN donation t on s.deliveryID = t.deliveryID 
                                        WHERE s.deliveredBy = '$employeeID' AND s.status = 'Completed'";

        $sql3 = "SELECT $cols,'ccdonation' AS type from subdelivery s 
                                        INNER JOIN ccdonation t on s.deliveryID = t.deliveryID 
                                        WHERE s.deliveredBy = '$employeeID' AND s.status = 'Completed'";

        // take union of deliveries related to all processes
        $sql = $sql1 . " UNION " . $sql2 . " UNION " . $sql3;

        // selects from the result of the select statement
        $stmt1 = self::prepare("SELECT * FROM ($sql) AS s $where");
        $stmt1->execute();
        return $stmt1->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * @param string $ccID
     * @return array
     */
    public static function getDeliveriesDoneUnderCCMonthBack(string $ccID) : array {
        $sql = "SELECT s.completedDate,COUNT(*) AS deliveries FROM subdelivery s
                INNER JOIN driver d ON s.deliveredBy = d.employeeID
                INNER JOIN users u ON d.employeeID = u.userID
                INNER JOIN communitycenter c ON d.ccID = c.ccID
                WHERE d.ccID = '$ccID' AND s.status = 'Completed' AND s.completedDate >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)
                GROUP BY s.completedDate";

        $statement = self::prepare($sql);
        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_KEY_PAIR);
    }

    /**
     * @param string $ccID
     * @return array
     */
    public static function getDeliveriesDoneUnderCCMonthBackByDistance(string $ccID) : array {
        $sql = "SELECT 
            CASE WHEN s.distance < 10 THEN 'Less than 10km'
            ELSE 'More than 10km' END AS distanceRange,
            COUNT(*) AS deliveries FROM subdelivery s
            INNER JOIN driver d ON s.deliveredBy = d.employeeID
            WHERE d.ccID = '$ccID' AND s.status = 'Completed' AND s.completedDate >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)
            GROUP BY distanceRange";

        $statement = self::prepare($sql);
        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_KEY_PAIR);

    }

    /**
     * @return array
     */
    public static function getDeliveriesDoneMonthBack() : array {
        $sql = "SELECT s.completedDate,COUNT(*) AS deliveries FROM subdelivery s
                INNER JOIN driver d ON s.deliveredBy = d.employeeID
                INNER JOIN users u ON d.employeeID = u.userID
                INNER JOIN communitycenter c ON d.ccID = c.ccID
                WHERE s.status = 'Completed' AND s.completedDate >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)
                GROUP BY s.completedDate";

        $statement = self::prepare($sql);
        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_KEY_PAIR);
    }

    /**
     * @return array
     */
    public static function getDeliveriesDoneMonthBackByDistance() : array {
        $sql = "SELECT 
            CASE WHEN s.distance < 10 THEN 'Less than 10km'
            ELSE 'More than 10km' END AS distanceRange,
            COUNT(*) AS deliveries FROM subdelivery s
            INNER JOIN driver d ON s.deliveredBy = d.employeeID
            WHERE s.status = 'Completed' AND s.completedDate >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)
            GROUP BY distanceRange";

        $statement = self::prepare($sql);
        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_KEY_PAIR);

    }

}