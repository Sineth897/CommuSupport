<?php

namespace app\models;

use app\core\DbModel;

class notificationModel extends DbModel
{
    public string $ID = '';
    public string $userID = '';
    public string $applicable = '';
    public string $usertype = '';
    public string $message = '';
    public string $title = '';
    public string $dateCreated = '';

    public function table(): string
    {
        return 'notifications';
    }

    public function attributes(): array
    {
        return ['userID','applicable', 'category' ,'message', 'type', 'status', 'dateCreated'];
    }

    public function primaryKey(): string
    {
        return 'ID';
    }

    public function rules(): array
    {
        return [

        ];
    }

    public function setNotification($message,$userID = null, $applicable = null, $usertype=null,) : bool
    {
        if($userID === null && $applicable === null && $usertype === null) {
            return false;
        }

        $this->userID = $userID;
        $this->applicable = $applicable;
        $this->usertype = $usertype;
        $this->message = $message;
        $this->dateCreated = date('Y-m-d H:i:s');
        return $this->save();
    }

    public function getNotification($where) : array
    {
        $sql = "SELECT * FROM notification WHERE $where";
        $statement = self::prepare($sql);
        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }
}