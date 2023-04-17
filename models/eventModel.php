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
            'contact' => [self::$REQUIRED,self::$CONTACT],
            'date' => [self::$REQUIRED,self::$DATE],
            'time' => [self::$REQUIRED],
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
        return ['eventID', 'eventCategoryID', 'theme', 'organizedBy', 'contact', 'date', 'time', 'location', 'description', 'status', 'participationCount','ccID'];
    }

    public function primaryKey(): string
    {
        return 'eventID';
    }

    public function save(): bool
    {
        $this->eventID = substr( uniqid('event',true),0,23);
        $manager = managerModel::getModel(['employeeID' => Application::session()->get('user')]);
        $this->ccID = $manager->ccID;
        return parent::save();
    }

    public function getEventCategories(): bool|array
    {
        $sql = "SELECT * FROM eventcategory";
        $stmt = self::prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_KEY_PAIR);
    }

    public static function getEventCategoryIcons() {
        $categories = (new static())->getEventCategories();
        $preparedIcons = [];
        foreach ($categories as $key => $value) {
            $preparedIcons[$key] = "/CommuSupport/public/src/icons/event/eventcategoryicons/" . $value . ".svg";
        }
        return $preparedIcons;
    }

    public function isGoing($eventID) {
        $sql = "SELECT * FROM eventparticipation WHERE eventID = :eventID AND userID = :doneeID";
        $stmt = self::prepare($sql);
        $stmt->bindValue(':eventID',$eventID);
        $stmt->bindValue(':doneeID',Application::session()->get('user'));
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    public static function markParticipation(string $eventID) {
        $userID = Application::session()->get('user');
        $sql = "INSERT INTO eventparticipation VALUES (:userID,:eventID)";
        $stmt = self::prepare($sql);
        $stmt->bindValue(':userID',$userID);
        $stmt->bindValue(':eventID',$eventID);
        $stmt->execute();
    }

    public static function unmarkParticipation(string $eventID) {
        $userID = Application::session()->get('user');
        $sql = "DELETE FROM eventparticipation WHERE eventID = :eventID AND userID = :userID";
        $stmt = self::prepare($sql);
        $stmt->bindValue(':eventID',$eventID);
        $stmt->bindValue(':userID',$userID);
        $stmt->execute();
    }

    public static function setParticipation(string $eventID) {
        $event = self::getModel(['eventID' => $eventID]);
        if($event->isGoing($eventID)) {
            self::unmarkParticipation($eventID);
            $event->participationCount--;
        }
        else {
            self::markParticipation($eventID);
            $event->participationCount++;
        }
        $event->update(['eventID' => $eventID],['participationCount' => $event->participationCount]);
    }
}