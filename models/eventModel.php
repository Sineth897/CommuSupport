<?php

namespace app\models;

use app\core\DbModel;

class eventModel extends DbModel
{
    public string $eventID = '';
    public string $eventCategory = '';
    public string $theme = '';
    public string $organizedBy = '';
    public string $contact = '';
    public string $date = '';
    public string $time = '';
    public string $location = '';
    public string $description = '';
    public string $status = 'Upcoming';
    public int $participationCount = 0;

    public function rules(): array
    {
        return [
            'eventCategory' => [self::$REQUIRED],
            'theme' => [self::$REQUIRED],
            'organizedBy' => [self::$REQUIRED],
            'contact' => [self::$REQUIRED,self::$CONTACT],
            'date' => [self::$REQUIRED],
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
        return ['eventID', 'eventCategory', 'theme', 'organizedBy', 'contact', 'date', 'time', 'location', 'description', 'status', 'participationCount'];
    }

    public function primaryKey(): string
    {
        return 'eventID';
    }

    public function save(): bool
    {
        $this->eventID = uniqid('event',true);
        return parent::save();
    }

    public function getEventCategories(): bool|array
    {
        $sql = "SELECT * FROM eventCategory";
        $stmt = self::prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_KEY_PAIR);
    }
}