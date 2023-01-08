<?php

namespace app\models;

use app\core\DbModel;

class choModel extends DbModel
{
    public string $choID = '';
    public string $contactNumber = '';
    public string $district = '';
    public string $email = '';
    public string $address = '';

    public function table(): string
    {
        return "communityheadoffice";
    }

    public function attributes(): array
    {
        return ['choID', 'contactNumber', 'district', 'email', 'address'];
    }

    public function primaryKey(): string
    {
        return 'choID';
    }

    public function rules(): array
    {
        return [
            'contactNumber' => [self::$REQUIRED, self::$CONTACT, [self::$UNIQUE, 'class' => self::class]],
            'district' => [self::$REQUIRED, [self::$UNIQUE, 'class' => self::class]],
            'email' => [self::$REQUIRED, self::$EMAIL, [self::$UNIQUE, 'class' => self::class]],
            'address' => [self::$REQUIRED, [self::$UNIQUE, 'class' => self::class]],
        ];
    }

    public function save(): bool
    {
        $this->choID = uniqid('cho',true);
        return parent::save();
    }

    public function setUser(userModel $user) {
        $this->user = $user;
        $this->user->userType = "cho";
        $this->user->userID = $this->choID;
    }

    public function getDistricts(): array {
        return [ "colombo" => "Colombo", "gampaha" => "Gampaha", "kalutara" => "Kalutara", "kandy" => "Kandy", "nuwaraeliya" => "Nuwara Eliya",
                "galle" => "Galle", "matara" => "Matara", "hambantota" => "Hambantota", "jaffna" => "Jaffna", "vavuniya" => "Vavuniya",
                "mannar" => "Mannar", "batticaloa" => "Batticaloa", "trincomalee" => "Trincomalee", "ampara" => "Ampara", "badulla" => "Badulla",
                "monaragala" => "Monaragala", "ratnapura" => "Ratnapura", "kegalle" => "Kegalle" ];
    }
}