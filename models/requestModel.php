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

    public function table(): string
    {
        return "request";
    }

    public function attributes(): array
    {
        return ["requestID", "postedBy", "item", "amount", "address", "urgency", "postedDate", "expDate", "notes"];
    }

    public function primaryKey(): string
    {
        return "requestID";
    }

    public function rules(): array
    {
        return [
            "item" => [self::$REQUIRED,],
            "amount" => [self::$REQUIRED, self::$POSITIVE],
            "urgency" => [self::$REQUIRED],
//            "notes" => [self::$REQUIRED],
        ];
    }

    public function getCategories(): array
    {
        $stmnt = self::prepare('SELECT * FROM category');
        $stmnt->execute();
        return $stmnt->fetchAll(\PDO::FETCH_KEY_PAIR);
    }

    public function getSubcategories($category)
    {
        $stmnt = self::prepare('SELECT subcategoryID,subcategoryName FROM subcategory WHERE categoryID = :category');
        $stmnt->bindValue(':category', $category);
        $stmnt->execute();
        return $stmnt->fetchAll(\PDO::FETCH_KEY_PAIR);
    }

    public function getUrgency(): array
    {
        return [
            'Within 7 days' => 'Within 7 days',
            'Within a month' => 'Within a month',
            'Within 3 month' => 'Within 3 month'
        ];
    }

    public function save(): bool
    {
        $this->requestID = substr(uniqid('request', true), 0, 23);
        $this->postedBy = $_SESSION['user'];
        $this->postedDate = date('Y-m-d');
        $this->expDate = date('Y-m-d', strtotime("+" . $this->getDays() . " days"));

        if ($this->address === "") {
            $user = doneeModel::getModel(['doneeID' => $_SESSION['user']]);
            $this->address = $user->address;
        }

        return parent::save();
    }

    private function getDays(): int
    {
        return match ($this->urgency) {
            'Within 7 days' => 7,
            'Within a month' => 30,
            'Within 3 month' => 90,
            default => 0,
        };
    }

    public function getRequestsUnderCC(string $ccID)
    {
        $stmnt = self::prepare('SELECT * FROM request r INNER JOIN subcategory s ON r.item = s.subcategoryID INNER JOIN category c ON s.categoryID = c.categoryID WHERE r.postedBy IN (SELECT doneeID FROM donee WHERE ccID = :ccID)');
        $stmnt->bindValue(':ccID', $ccID);
        $stmnt->execute();
        return $stmnt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function rejectRequest(string $rejectedReason)
    {
        $cols = ['requestID', 'postedBy', 'item', 'amount', 'address', 'urgency', 'postedDate', 'notes'];
        $colstring = implode(',', array_map(fn($col) => ":$col", $cols));
        $sql = 'INSERT INTO rejectedrequest VALUES (' . $colstring . ',:rejectedDate,:rejectedReason)';
        $stmnt = self::prepare($sql);
        foreach ($cols as $attribute) {
            $stmnt->bindValue(":$attribute", $this->{$attribute});
        }
        $stmnt->bindValue(':rejectedDate', date('Y-m-d'));
        $stmnt->bindValue(':rejectedReason', $rejectedReason);
        $stmnt->execute();
        $this->delete(['requestID' => $this->requestID]);
    }

    public function getOwnRequests(string $doneeID): array
    {
        return [
            'activeRequests' => $this->getPostedRequestsbyDonee($doneeID),
            'completedRequests' => $this->getCompletedRequestsOfDonee($doneeID),
            'acceptedRequests' => $this->getAcceptedRequestsOfDonee($doneeID)
        ];
    }

    private function getPostedRequestsbyDonee(string $doneeID): array
    {
        $stmnt = self::prepare('SELECT * FROM request r INNER JOIN subcategory s ON r.item = s.subcategoryID INNER JOIN category c ON s.categoryID = c.categoryID WHERE r.postedBy = :doneeID');
        $stmnt->bindValue(':doneeID', $doneeID);
        $stmnt->execute();
        return $stmnt->fetchAll(\PDO::FETCH_ASSOC);
    }

    private function getCompletedRequestsOfDonee(string $doneeID): array
    {
        $stmnt = self::prepare("SELECT * FROM acceptedrequest r INNER JOIN subcategory s ON r.item = s.subcategoryID INNER JOIN category c ON s.categoryID = c.categoryID WHERE r.postedBy = :doneeID AND r.status = 'Completed'");
        $stmnt->bindValue(':doneeID', $doneeID);
        $stmnt->execute();
        return $stmnt->fetchAll(\PDO::FETCH_ASSOC);
    }

    private function getAcceptedRequestsOfDonee(string $doneeID): array
    {
        $stmnt = self::prepare("SELECT * FROM acceptedrequest r INNER JOIN subcategory s ON r.item = s.subcategoryID INNER JOIN category c ON s.categoryID = c.categoryID WHERE r.postedBy = :doneeID AND r.status = 'Accepted' AND r.requestID NOT IN (SELECT requestID FROM request)");
        $stmnt->bindValue(':doneeID', $doneeID);
        $stmnt->execute();
        return $stmnt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getAllRequests(array $where = [])
    {
        $sql = 'SELECT * FROM request r INNER JOIN subcategory s ON r.item = s.subcategoryID INNER JOIN category c ON s.categoryID = c.categoryID';
        if (empty($where)) {
            $stmnt = self::prepare($sql);
            $stmnt->execute();
            return $stmnt->fetchAll(\PDO::FETCH_ASSOC);
        }
        $sql .= ' WHERE ';
        if (in_array('Approved', $where)) {
            $sql .= "r.approval = 'Approved'";
            unset($where['approval']);
        }
        $stmnt = self::prepare($sql);
        $stmnt->execute();
        return $stmnt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function accept(): acceptedModel
    {
        $acceptedRequest = new acceptedModel();
        $acceptedRequest->acceptedID = substr(uniqid('accepted', true), 0, 23);
        $acceptedRequest->getDataFromThePostedRequest($this);
        return $acceptedRequest;
    }

    public function getPendingRequestWithPostedBy(): array
    {
        $cols = "r.requestID,u.username,r.approval,r.postedDate,s.subcategoryName, CONCAT(r.amount,' ',s.scale) as amount";
        $sql = 'SELECT ' . $cols . ' FROM request r INNER JOIN users u ON r.postedBy = u.userID INNER JOIN subcategory s on r.item = s.subcategoryID';

        $stmnt = self::prepare($sql);
        $stmnt->execute();
        return $stmnt->fetchALL(\PDO::FETCH_ASSOC);
    }

    public function getAcceptedRequestWithPostedBy()
    {
        $cols1 = "r.acceptedID, u.username, r.acceptedBy, r.deliveryStatus, s.subcategoryName, CONCAT(r.amount,' ',s.scale) as amount";
        $sql1 = 'SELECT ' . $cols1 . ' FROM acceptedrequest r LEFT JOIN users u ON r.postedBy = u.userID LEFT JOIN subcategory s on r.item = s.subcategoryID';

        $stmnt1 = self::prepare($sql1);
        $stmnt1->execute();
        $requests = $stmnt1->fetchALL(\PDO::FETCH_ASSOC);

        $sql2 = "SELECT userID,username as acceptedBy FROM users WHERE userType = 'donor' UNION ALL SELECT ccID,CONCAT(city,' (CC)') as acceptedBY FROM communitycenter";

        try {
            $stmnt2 = self::prepare($sql2);
            $stmnt2->execute();
            $acceptedBy = $stmnt2->fetchALL(\PDO::FETCH_KEY_PAIR);
            foreach ($requests as &$request) {
                $request['acceptedBy'] = $acceptedBy[$request['acceptedBy']];
            }
        } catch (\PDOException $e) {
            echo $e->getMessage();
        }
        return $requests;
    }

    public static function getAllSubcategories()
    {
        $stmnt = self::prepare('SELECT subcategoryID,subcategoryName FROM subcategory');
        $stmnt->execute();
        return $stmnt->fetchAll(\PDO::FETCH_KEY_PAIR);
    }


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