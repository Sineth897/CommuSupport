<?php

namespace app\models;

use app\core\Application;
use app\core\DbModel;

class ccModel extends DbModel
{
    public string $ccID = "";
    public string $address = "";
    public string $city = "";
    public string $email = "";
    public string $fax = "";
    public string $contactNumber = "";
    public string $cho = "";

    public function table(): string
    {
        return "communityCenter";
    }

    public function attributes(): array
    {
        return ["ccID", "address", "city", "email", "fax", "contactNumber", "cho"];
    }

    public function primaryKey(): string
    {
        return "ccID";
    }

    public function rules(): array
    {
        return [

            "address" => [self::$REQUIRED, [self::$UNIQUE, "class" => self::class]],
            "city" => [self::$REQUIRED, [self::$UNIQUE, 'class' => self::class]],
            "email" => [self::$REQUIRED, self::$EMAIL, [self::$UNIQUE, "class" => self::class]],
            "fax" => [self::$REQUIRED, [self::$UNIQUE, 'class' => self::class]],
            "contactNumber" => [self::$REQUIRED, [self::$UNIQUE, 'class' => self::class]],
            "cho" => [self::$REQUIRED]

        ];
    }


    public function getCoordinates()
    {
        $stmnt = self::prepare("SELECT ccID,longitude,latitude FROM communitycenter");
        $stmnt->execute();
        return $stmnt->fetchALL(\PDO::FETCH_ASSOC);
    }

    public function save(): bool
    {
        $this->ccID = uniqid('cc', true);
        $cho = choModel::getUser(['choID' => Application::session()->get('user')]);
        $this->cho = $cho->choID;
        if (parent::save()) {
            if ($this->user->save()) {
                return true;
            } else {
                $this->delete(['choID' => $this->choID]);
                return false;
            }
        }
        return false;

    }

}