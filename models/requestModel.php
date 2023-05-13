<?php

namespace app\models;

use app\core\DbModel;

class requestModel extends DbModel
{

    public string $requestID = "";
    public string $postedBy = "";
    public string $approval = "";
    public ?string $approvedDate = "";
    public string $item = "";
    public string $amount = "";
    public string $address = "";
    public string $urgency = "";
    public string $postedDate = "";
    public string $expDate = "";
    public string $notes = "";

    /**
     * @return string
     */
    public function table(): string
    {
        return "request";
    }

    /**
     * @return string[]
     */
    public function attributes(): array
    {
        return ["requestID", "postedBy", "item", "amount", "address", "urgency", "postedDate", "expDate", "notes"];
    }

    /**
     * @return string
     */
    public function primaryKey(): string
    {
        return "requestID";
    }

    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            "item" => [self::$REQUIRED,],
            "amount" => [self::$REQUIRED, self::$POSITIVE],
            "urgency" => [self::$REQUIRED],
        ];
    }

    /**
     * @return array
     */
    public function getCategories(): array
    {

        // function retreive all categories as key value pairs
        $stmnt = self::prepare('SELECT * FROM category');
        $stmnt->execute();
        return $stmnt->fetchAll(\PDO::FETCH_KEY_PAIR);

    }

    /**
     * @return array
     */
    public static function getCategoriesStatic(): array
    {

        // function retreive all categories as key value pairs
        $stmnt = self::prepare('SELECT * FROM category');
        $stmnt->execute();
        return $stmnt->fetchAll(\PDO::FETCH_KEY_PAIR);

    }

    /**
     * @param $category
     * @return array
     */
    public function getSubcategories($category) : array
    {

        // function retreive all subcategories as key value pairs when category ID is given
        $stmnt = self::prepare('SELECT subcategoryID,subcategoryName FROM subcategory WHERE categoryID = :category');
        $stmnt->bindValue(':category', $category);
        $stmnt->execute();
        return $stmnt->fetchAll(\PDO::FETCH_KEY_PAIR);

    }

    /**
     * @return string[]
     */
    public function getUrgency(): array
    {

        // function to retreive urgency options, this is defined as company policies
        return [
            'Within 7 days' => 'Within 7 days',
            'Within a month' => 'Within a month',
            'Within 3 month' => 'Within 3 month'
        ];

    }


    /**
     * @return bool
     */
    public function save(): bool
    {

        // override parent save function to add additional attributes

        // generate uniq request ID where length is 23
        $this->requestID = substr(uniqid('request', true), 0, 23);

        // set posted by as current user
        $this->postedBy = $_SESSION['user'];

        // set posted date as current date
        $this->postedDate = date('Y-m-d');

        // set expiry date as current date + urgency days
        $this->expDate = date('Y-m-d', strtotime("+" . $this->getDays() . " days"));

        // if address is empty, set address as current user's address
        if ($this->address === "") {
            $user = doneeModel::getModel(['doneeID' => $_SESSION['user']]);
            $this->address = $user->address;
        }

        // call parent save function
        return parent::save();

    }

    private function getDays(): int
    {

        // function to return number of days for urgency
        return match ($this->urgency) {
            'Within 7 days' => 7,
            'Within a month' => 30,
            'Within 3 month' => 90,
            default => 0,
        };

    }

    /**
     * @param string $ccID
     * @return array
     */
    public function getRequestsUnderCC(string $ccID) : array
    {

        // function to retreive all requests under a given CC, where they are not accepted yet
        $stmnt = self::prepare('SELECT * FROM request r 
                                        INNER JOIN subcategory s ON r.item = s.subcategoryID 
                                        INNER JOIN category c ON s.categoryID = c.categoryID 
                                        WHERE r.postedBy IN (SELECT doneeID FROM donee WHERE ccID = :ccID)');

        $stmnt->bindValue(':ccID', $ccID);
        $stmnt->execute();
        return $stmnt->fetchAll(\PDO::FETCH_ASSOC);

    }

    /**
     * @param array $filter
     * @param array $sort
     * @return array
     */
    public static function getRequestsUnderCCFilteredAndSorted(string $ccID,array $filter, array $sort) : array {

        // function to retreive all requests under a given CC, where they are not accepted yet
        // this function is used to filter and sort the requests
        $sql = "SELECT * FROM request r 
                INNER JOIN subcategory s ON r.item = s.subcategoryID 
                INNER JOIN category c ON s.categoryID = c.categoryID 
                WHERE r.postedBy IN (SELECT doneeID FROM donee WHERE ccID = '{$ccID}')";

        // if filter is not empty, append filter to the sql string
        if (!empty($filter)) {
            $sql .= ' AND ' . implode(' AND ', array_map(fn($col) => "$col = :$col", array_keys($filter)));
        }

        // if sort is not empty, append sort to the sql string
        if (!empty($sort['DESC'])) {
            $sql .= ' ORDER BY ' . implode(',', array_map(fn($col) => "$col", $sort['DESC']));
        }

        // prepare the statement
        $stmnt = self::prepare($sql);


        // bind values to the statement
        foreach ($filter as $attribute => $value) {
            $stmnt->bindValue(":$attribute", $value);
        }

        $stmnt->execute();
        return $stmnt->fetchAll(\PDO::FETCH_ASSOC);

    }

    /**
     * @param string $rejectedReason
     * @return void
     */
    public function rejectRequest(string $rejectedReason) : void
    {

        // function to reject a request, where it will be moved to rejected request table
        // columns that will be inserted to the table
        $cols = ['requestID', 'postedBy', 'item', 'amount', 'address', 'urgency', 'postedDate', 'notes'];

        // implode columns to a string
        $colstring = implode(',', array_map(fn($col) => ":$col", $cols));

        // get sql string after appending rejected date and rejected reason and prepare the statement
        $sql = 'INSERT INTO rejectedrequest VALUES (' . $colstring . ',:rejectedDate,:rejectedReason)';
        $stmnt = self::prepare($sql);

        // bind values to the statement
        foreach ($cols as $attribute) {
            $stmnt->bindValue(":$attribute", $this->{$attribute});
        }

        // bind rejected date and rejected reason
        $stmnt->bindValue(':rejectedDate', date('Y-m-d'));
        $stmnt->bindValue(':rejectedReason', $rejectedReason);
        $stmnt->execute();

        // delete the request from request table
        $this->delete(['requestID' => $this->requestID]);

    }

    /**
     * @param string $doneeID
     * @return array
     */
    public function getOwnRequests(string $doneeID): array
    {

        // function to retreive all requests posted by a given donee
        return [
            'activeRequests' => $this->getPostedRequestsbyDonee($doneeID),
            'completedRequests' => $this->getCompletedRequestsOfDonee($doneeID),
            'acceptedRequests' => $this->getAcceptedRequestsOfDonee($doneeID)
        ];

    }

    /**
     * @param string $doneeID
     * @return array
     */
    private function getPostedRequestsbyDonee(string $doneeID) : array
    {

        // function to retreive all active requests posted by a given donee where some amount has been accepted by other users
        $stmnt = self::prepare("SELECT r.*,CONCAT(r.amount,' ',s.scale) AS amount,s.*,'category' AS categoryName,
                                            COUNT(a.acceptedBy) AS users,
                                            CONCAT(SUM(a.amount),' ',s.scale) AS acceptedAmount FROM request r 
                                            LEFT JOIN subcategory s ON r.item = s.subcategoryID 
                                            LEFT JOIN acceptedrequest a on a.requestID = r.requestID 
                                            WHERE r.postedBy = :doneeID GROUP BY a.requestID");

        // bind donee ID to the statement
        $stmnt->bindValue(':doneeID', $doneeID);
        $stmnt->execute();
        return $stmnt->fetchAll(\PDO::FETCH_ASSOC);

    }

    /**
     * @param string $doneeID
     * @return array
     */
    private function getCompletedRequestsOfDonee(string $doneeID): array
    {

        // function to retreive all completed requests posted by a given donee
        $stmnt = self::prepare("SELECT *,CONCAT(SUM(r.amount),' ',s.scale) AS amount,
                                        COUNT(r.requestID) AS users FROM acceptedrequest r 
                                        INNER JOIN subcategory s ON r.item = s.subcategoryID 
                                        INNER JOIN category c ON s.categoryID = c.categoryID 
                                        WHERE r.postedBy = :doneeID AND r.deliveryStatus = 'Completed' 
                                        GROUP BY r.requestID");

        // bind donee ID to the statement
        $stmnt->bindValue(':doneeID', $doneeID);
        $stmnt->execute();
        return $stmnt->fetchAll(\PDO::FETCH_ASSOC);

    }

    /**
     * @param string $doneeID
     * @return array
     */
    private function getAcceptedRequestsOfDonee(string $doneeID): array
    {

        // function to retreive all accepted requests posted by a given donee where some amount has been accepted by other users
        $stmnt = self::prepare("SELECT * FROM acceptedrequest r 
                                        INNER JOIN subcategory s ON r.item = s.subcategoryID 
                                        INNER JOIN category c ON s.categoryID = c.categoryID 
                                        WHERE r.postedBy = :doneeID AND r.deliveryStatus != 'Completed'");

        // bind donee ID to the statement
        $stmnt->bindValue(':doneeID', $doneeID);
        $stmnt->execute();
        return $stmnt->fetchAll(\PDO::FETCH_ASSOC);

    }

    /**
     * @param array $where
     * @return array
     */
    public function getAllRequests(array $where = []) : array
    {

        // function to retreive all requests from request table
        $sql = 'SELECT * FROM request r 
                    INNER JOIN subcategory s ON r.item = s.subcategoryID 
                    INNER JOIN category c ON s.categoryID = c.categoryID';

        // if where array is empty, execute the sql statement
        if (empty($where)) {
            $stmnt = self::prepare($sql);
            $stmnt->execute();
            return $stmnt->fetchAll(\PDO::FETCH_ASSOC);
        }

        // else append where clause to the sql statement
        $sql .= ' WHERE ';
        if (in_array('Approved', $where)) {
            $sql .= "r.approval = 'Approved'";
            unset($where['approval']);
        }

        // prepare and execute the sql statement
        $stmnt = self::prepare($sql);
        $stmnt->execute();
        return $stmnt->fetchAll(\PDO::FETCH_ASSOC);

    }

    /**
     * @return acceptedModel
     */
    public function accept(): acceptedModel
    {

        // function to create an accepted request object from the posted request
        $acceptedRequest = new acceptedModel();

        // set accepted ID to a unique value
        $acceptedRequest->acceptedID = substr(uniqid('accepted', true), 0, 23);

        // set the attributes of the accepted request object from this object
        $acceptedRequest->getDataFromThePostedRequest($this);
        return $acceptedRequest;

    }

    /**
     * @return array
     */
    public function getPendingRequestWithPostedBy(): array
    {

        // function to retreive all pending requests with the posted by user
        $cols = "r.requestID,u.username,r.approval,r.postedDate,s.subcategoryName, CONCAT(r.amount,' ',s.scale) as amount";
        $sql = 'SELECT ' . $cols . ' FROM request r INNER JOIN users u ON r.postedBy = u.userID INNER JOIN subcategory s on r.item = s.subcategoryID';

        // prepare and execute the sql statement
        $stmnt = self::prepare($sql);
        $stmnt->execute();
        return $stmnt->fetchALL(\PDO::FETCH_ASSOC);

    }

    /**
     * @return array
     */
    public function getAcceptedRequestWithPostedBy() : array
    {

        try {

            // function to retreive all accepted requests with the posted by user
            $cols1 = "r.acceptedID, u.username, r.acceptedBy, r.deliveryStatus, s.subcategoryName, CONCAT(r.amount,' ',s.scale) as amount";
            $sql1 = 'SELECT ' . $cols1 . ' FROM acceptedrequest r LEFT JOIN users u ON r.postedBy = u.userID LEFT JOIN subcategory s on r.item = s.subcategoryID';

            // prepare and execute the sql statement
            $stmnt1 = self::prepare($sql1);
            $stmnt1->execute();
            $requests = $stmnt1->fetchALL(\PDO::FETCH_ASSOC);

            // get the accepted by user
            // user can either be a donor or a community center
            $sql2 = "SELECT userID,username as acceptedBy FROM users WHERE userType = 'donor' 
                            UNION ALL SELECT ccID,CONCAT(city,' (CC)') as acceptedBY FROM communitycenter";



            // prepare and execute the sql statement, retrieve as key value pairs
            $stmnt2 = self::prepare($sql2);
            $stmnt2->execute();
            $acceptedBy = $stmnt2->fetchALL(\PDO::FETCH_KEY_PAIR);

            // replace the accepted by user ID with the username
            foreach ($requests as &$request) {
                $request['acceptedBy'] = $acceptedBy[$request['acceptedBy']];
            }

        } catch (\PDOException $e) {

            // if an exception occurs, print the error message
            echo $e->getMessage();

        }

        /** @var array $requests */
        return $requests;

    }

    /**
     * @return array
     */
    public static function getAllSubcategories() : array
    {

        // function to retreive all subcategories as key value pairs
        $stmnt = self::prepare('SELECT subcategoryID,subcategoryName FROM subcategory');
        $stmnt->execute();
        return $stmnt->fetchAll(\PDO::FETCH_KEY_PAIR);

    }

    /**
     * @return array
     */
    public function getRequestDataMonthly(): array
    {
        $urgencies = array("Within 7 days", "Within a month");
        $results = array();
        foreach ($urgencies as $urgency) {
            // Create an array with all 12 months of the year
            $monthsOfYear = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
            // Get the count of requests published on each month for urgency = "Within 7 days"
            $sql = "SELECT COUNT(*) as count, MONTHNAME(postedDate) as month FROM request WHERE urgency = :urgency GROUP BY MONTH(postedDate)";
            $statement = requestModel::prepare($sql);
            $statement->execute(array(":urgency" => $urgency));
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            // Loop through the result and update the corresponding value in the new array
            $chartData = array_fill_keys($monthsOfYear, 0);
            foreach ($result as $row) {
                $chartData[$row['month']] = $row['count'];
            }
            $results[$urgency] = $chartData;
        }
        return $results;
    }

    /**
     * @return array
     */
    public function getRequestDatabyCategory() : array
    {
        // Get the count of requests published on each month for urgency = "Within 7 days"
        $sql = "SELECT c.categoryName, COUNT(r.item) as count FROM request r INNER JOIN subcategory s ON r.item = s.subcategoryID RIGHT JOIN category c ON s.categoryID = c.categoryID GROUP BY c.categoryName";
        $statement = requestModel::prepare($sql);
        $statement->execute();
        $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $chartData = array();
        // Loop through the result and update the corresponding value in the new array
        foreach ($result as $row) {
            $chartData[$row['categoryName']] = $row['count'];
        }
        return $chartData;
    }

}