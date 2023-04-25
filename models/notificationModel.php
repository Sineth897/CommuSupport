<?php

namespace app\models;

use app\core\DbModel;

class notificationModel extends DbModel
{
    public string $ID = '';
    public string $userID = ''; // to whom the notification is sent can be null  this takes the form of "ID1,ID2,...." of the users
    public string $related = ''; // related processs means whether deliveries, events, donations, etc. can be null this takes the form of "request,delivery,...."
    public string $relatedID = ''; // related processes ID means whether deliveries, events, donations, etc. can be null this takes the form of "ID1,ID2,...." od the applicables
    public string $usertype = ''; // to what type of user the notification is applicable can be null this takes the form of "admin,manager,...."
    public string $message = '';
    public string $title = ''; // title of the notification
    public string $dateCreated = '';

    public function table(): string
    {
        return 'notification';
    }

    public function attributes(): array
    {
        return ['userID','applicable','message', 'usertype', 'dateCreated','title'];
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

    public static function setNotification($message,$title,$userID = '', $usertype='',$related = '',$relatedID = '') : bool
    {
        if(!$userID && !$related && !$usertype && !$relatedID) {
            return false;
        }

        $sql = "INSERT INTO notification (message,title,userID,usertype,related,relatedID,dateCreated) VALUES (:message,:title,:userID,:usertype,:related,:relatedID,:dateCreated)";
        $statement = self::prepare($sql);
        $statement->bindValue(':message',$message);
        $statement->bindValue(':title',$title);
        $statement->bindValue(':userID',$userID);
        $statement->bindValue(':usertype',$usertype);
        $statement->bindValue(':related',$related);
        $statement->bindValue(':relatedID',$relatedID);
        $statement->bindValue(':dateCreated',date('Y-m-d H:i:s'));
        return $statement->execute();
    }

    public static function getNotification(array $where) : array
    {
        $pattern  = '';
        if($where['userID']) {
            $pattern .= "`userID` LIKE '%$where[userID]%'";
        }
        if($where['usertype']) {
            $pattern .= $pattern ? " OR " : "";
            $pattern .= "`usertype` LIKE '%$where[usertype]%'";
        }
//        if(!empty($where['related'])) {
//            foreach ( $where['related'] as $value) {
//                $pattern .= $pattern ? " OR " : "";
//                $pattern .= "`related` LIKE '%$value%'";
//            }
//        }
        $sql = "SELECT *,TIMEDIFF(CURRENT_TIMESTAMP,dateCreated) as timeDifference FROM notification WHERE " . $pattern . " ORDER BY dateCreated DESC";
//        echo $sql;
        $statement = self::prepare($sql);
//        foreach ($where as $key => $value) {
//            $statement->bindValue(":$key","%" . $value . "%");
//        }
        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }
}