<?php

namespace app\models;

use app\core\Application;
use app\core\DbModel;

class eventModel extends DbModel
{
    public string $eventID = '';
    public string $eventCategoryID = '';
    public string $theme = '';
    public string $organizedBy = '';
    public string $contact = '';
    public string $date = '';
    public string $time = '';
    public string $location = '';
    public string $description = '';
    public string $status = 'Upcoming';
    public int $participationCount = 0;

    public string $ccID = '';

    public function rules(): array
    {
        return [
            'eventCategoryID' => [self::$REQUIRED],
            'theme' => [self::$REQUIRED],
            'organizedBy' => [self::$REQUIRED],
            'contact' => [self::$REQUIRED, self::$CONTACT],
            'date' => [self::$REQUIRED, self::$DATE],
            'time' => [self::$REQUIRED,self::$TIME],
            'location' => [self::$REQUIRED],
            'description' => [self::$REQUIRED],
        ];

    }

    public function table(): string
    {
        return 'event';
    }

    public function attributes(): array
    {
        return ['eventID', 'eventCategoryID', 'theme', 'organizedBy', 'contact', 'date', 'time', 'location', 'description', 'status', 'participationCount', 'ccID'];
    }

    public function primaryKey(): string
    {
        return 'eventID';
    }

    public function save(): bool
    {
        $this->eventID = substr(uniqid('event', true), 0, 23);
        $manager = managerModel::getModel(['employeeID' => Application::session()->get('user')]);
        $this->ccID = $manager->ccID;
        return parent::save();
    }

    /**
     * @return bool|array
     */
    public function getEventCategories(): bool|array
    {
        $sql = "SELECT * FROM eventcategory";
        $stmt = self::prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_KEY_PAIR);
    }

    /**
     * @return array
     */
    public static function getEventCategoryIcons() : array
    {
        $categories = (new static())->getEventCategories();
        $preparedIcons = [];
        foreach ($categories as $key => $value) {
            $preparedIcons[$key] = "/CommuSupport/public/src/icons/event/eventcategoryicons/" . $value . ".svg";
        }
        return $preparedIcons;
    }

    /**
     * @param $eventID
     * @return bool
     */
    public function isGoing($eventID) : bool
    {
        $sql = "SELECT * FROM eventparticipation WHERE eventID = :eventID AND userID = :doneeID";
        $stmt = self::prepare($sql);
        $stmt->bindValue(':eventID', $eventID);
        $stmt->bindValue(':doneeID', Application::session()->get('user'));
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    /**
     * @param string $eventID
     * @return void
     */
    public static function markParticipation(string $eventID) : void
    {
        $userID = Application::session()->get('user');
        $sql = "INSERT INTO eventparticipation VALUES (:userID,:eventID)";
        $stmt = self::prepare($sql);
        $stmt->bindValue(':userID', $userID);
        $stmt->bindValue(':eventID', $eventID);
        $stmt->execute();
    }

    /**
     * @param string $eventID
     * @return void
     */
    public static function unmarkParticipation(string $eventID) : void
    {
        $userID = Application::session()->get('user');
        $sql = "DELETE FROM eventparticipation WHERE eventID = :eventID AND userID = :userID";
        $stmt = self::prepare($sql);
        $stmt->bindValue(':eventID', $eventID);
        $stmt->bindValue(':userID', $userID);
        $stmt->execute();
    }

    /**
     * @param string $eventID
     * @return void
     */
    public static function setParticipation(string $eventID) : void
    {
        $event = self::getModel(['eventID' => $eventID]);
        if ($event->isGoing($eventID)) {
            self::unmarkParticipation($eventID);
            $event->participationCount--;
        } else {
            self::markParticipation($eventID);
            $event->participationCount++;
        }
        $event->update(['eventID' => $eventID], ['participationCount' => $event->participationCount]);
    }

    /**
     * @return array
     */
    public function getAllUpcominAndActiveEvents() : array {
        $sql = "SELECT * FROM event WHERE status = 'Upcoming' OR status = 'Active'";
        $stmt = self::prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

//    To create a chart that shows event participation per each event category
    public function getEventbyCategory(): array
    {
        $sql = "SELECT ec.name, COUNT(*) as count FROM event e RIGHT JOIN eventcategory ec ON e.eventCategoryID = ec.eventCategoryID GROUP BY ec.name";
        $stmt = self::prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $chartData = array();
        // Loop through the result and update the corresponding value in the new array
        foreach ($result as $row) {
            $chartData[$row['name']] = $row['count'];
        }
        return $chartData;
    }

    public function getEventPartbyMonth(): array
    {
        $eventcategories = $this->getEventCategories();
        $results[] = array();
        foreach ($eventcategories as $eventcategory) {
            // Create an array with all 12 months of the year
            $monthsOfYear = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
            // Get the count of requests published on each month for urgency = "Within 7 days"
            $sql = "SELECT ec.name, MONTHNAME(e.date) AS month, SUM(e.participationCount) AS count FROM event e INNER JOIN eventcategory ec ON e.eventCategoryID = ec.eventCategoryID WHERE ec.name = :eventCategory GROUP BY MONTH(e.date);";
            $statement = eventModel::prepare($sql);
            $statement->execute(array(":eventCategory" => $eventcategory));
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            // Loop through the result and update the corresponding value in the new array
            $chartData = array_fill_keys($monthsOfYear, 0);
            foreach ($result as $row) {
                $chartData[$row['month']] = $row['count'];
            }
            $results[$eventcategory] = $chartData;
        }
        return $results;
    }

    /**
     * @return array
     */
    public static function getEventDetailsMonthBack() : array {

//        $sql = "SELECT * FROM event e
//                    INNER JOIN communitycenter c on e.ccID = c.ccID
//                    INNER JOIN eventcategory e2 on e.eventCategoryID = e2.eventCategoryID
//                    WHERE date >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)";
        $sql = "SELECT * FROM event e 
                    INNER JOIN communitycenter c on e.ccID = c.ccID 
                    INNER JOIN eventcategory e2 on e.eventCategoryID = e2.eventCategoryID";
                    ;
        $stmt = self::prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);

    }

    /**
     * @return array
     */
    public static function getEventDetailsWithTypeMonthBack() : array {

        $sql = "SELECT name,COUNT(*) FROM event e 
                    INNER JOIN communitycenter c on e.ccID = c.ccID 
                    INNER JOIN eventcategory e2 on e.eventCategoryID = e2.eventCategoryID
                    WHERE date >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)
                    GROUP BY name";
        $sql = "SELECT name,COUNT(*) FROM event e 
                    INNER JOIN communitycenter c on e.ccID = c.ccID 
                    INNER JOIN eventcategory e2 on e.eventCategoryID = e2.eventCategoryID
                    GROUP BY name";
        $stmt = self::prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_KEY_PAIR);

    }

    /**
     * @return array
     */
    public static function getEventFinishedMonthBack() : array {

        $sql = "SELECT date,COUNT(*) FROM event 
                    WHERE status = 'Finished' 
                    AND date >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)
                    GROUP BY date";

        $sql = "SELECT date,COUNT(*) FROM event 
                    WHERE status = 'Finished'
                    GROUP BY date";

        $stmt = self::prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_KEY_PAIR);
    }

    public function getEventSums()
    {
        $sql = "SELECT status, COUNT(*) FROM event GROUP BY status";
        $stmt = self::prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_KEY_PAIR);
    }
}