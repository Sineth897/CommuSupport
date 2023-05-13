<?php

namespace app\models;

use app\core\DbModel;

class choModel extends DbModel
{
    protected userModel $user;
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
        $this->choID = substr(uniqid('cho',true),0,23);
        $this->user->userID = $this->choID;
        $this->user->password = password_hash($this->user->password, PASSWORD_DEFAULT);
        $this->user->userType = "cho";
        if(parent::save()){
            if($this->user->save()){
                return true;
            }
        }
        return false;
    }

    /**
     * @param userModel $user
     * @return void
     */
    public function setUser(userModel $user) : void {
        $this->user = $user;
        $this->user->userType = "cho";
        $this->user->userID = $this->choID;
    }

    /**
     * @return string[]
     */
    public function getDistricts(): array {
        return [ "colombo" => "Colombo", "gampaha" => "Gampaha", "kalutara" => "Kalutara", "kandy" => "Kandy", "nuwaraeliya" => "Nuwara Eliya",
                "galle" => "Galle", "matara" => "Matara", "hambantota" => "Hambantota", "jaffna" => "Jaffna", "vavuniya" => "Vavuniya",
                "mannar" => "Mannar", "batticaloa" => "Batticaloa", "trincomalee" => "Trincomalee", "ampara" => "Ampara", "badulla" => "Badulla",
                "monaragala" => "Monaragala", "ratnapura" => "Ratnapura", "kegalle" => "Kegalle" ];
    }

    public static function getCHOs(): array {
        $stmnt = self::prepare("SELECT choID,district FROM communityheadoffice");
        $stmnt->execute();
        return $stmnt->fetchAll(\PDO::FETCH_KEY_PAIR);
    }

    public static function getCCsUnderCHO($choID): array {
        $stmnt = self::prepare("SELECT ccID,city FROM communitycenter WHERE cho = :choID");
        $stmnt->bindValue(":choID", $choID);
        $stmnt->execute();
        return $stmnt->fetchAll(\PDO::FETCH_KEY_PAIR);
    }

    //community center view users registered under each
    public static function viewUsers($choID):array{

        $statement =  self::prepare("SELECT u.userName,u.userType,d.email,d.address,d.contactNumber,d.type,d.registeredDate FROM users u  INNER JOIN donor d ON u.userID=d.donorID INNER JOIN communitycenter c ON d.ccID=c.ccID WHERE cho=:choID UNION SELECT u.userName,u.userType,d.email,d.address,d.contactNumber,d.type,d.registeredDate FROM users u  INNER JOIN donee d ON u.userID=d.doneeID INNER JOIN communitycenter c ON d.ccID=c.ccID WHERE cho=:choID");
        $statement->bindValue(":choID",$choID);
        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * @return array
     */
    public function getCHOInformationForProfile() : array {
        return [
            $this->getPersonalInfo()[0],
            $this->getCHOStatistics(),
        ];
    }

    /**
     * @return array
     */
    private function getPersonalInfo() : array {

        $sql = "SELECT * FROM users u
                    INNER JOIN communityheadoffice c ON u.userID = c.choID
                    WHERE u.userID = '{$_SESSION['user']}'";

        $statement = self::prepare($sql);
        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * @return array
     */
    private function getCHOStatistics() : array {

        $arrayOfSql = [
            $sqlCommunityCenters = "SELECT 'Community Centers',COUNT(*) FROM communitycenter c
                                            WHERE c.cho = '{$_SESSION['user']}'",

            $sqlDrivers = "SELECT 'Drivers',COUNT(*) FROM driver d
                                            WHERE d.ccID IN (SELECT c.ccID FROM communitycenter c
                                            WHERE c.cho = '{$_SESSION['user']}')",

            $sqlDonees = "SELECT 'Donees',COUNT(*) FROM donee d
                                            WHERE d.ccID IN (SELECT c.ccID FROM communitycenter c
                                            WHERE c.cho = '{$_SESSION['user']}')",

            $sqlDonees = "SELECT 'Donors',COUNT(*) FROM donor d
                                            WHERE d.ccID IN (SELECT c.ccID FROM communitycenter c
                                            WHERE c.cho = '{$_SESSION['user']}')",

            $sqlActiveComplaints = "SELECT 'Complaints Filed This Month',COUNT(*) FROM complaint c
                                            WHERE c.choID = '{$_SESSION['user']}' AND c.status = 'Pending'
                                            AND MONTH(c.filedDate) = MONTH(CURRENT_DATE()) AND YEAR(c.filedDate) = YEAR(CURRENT_DATE())",

            $sqlActiveComplaints = "SELECT 'Complaints Solved This Month',COUNT(*) FROM complaint c
                                            WHERE c.choID = '{$_SESSION['user']}' AND c.status = 'Solved'
                                            AND MONTH(c.filedDate) = MONTH(CURRENT_DATE()) AND YEAR(c.filedDate) = YEAR(CURRENT_DATE())",
        ];

        $statement = self::prepare(implode(" UNION ",$arrayOfSql));
        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_KEY_PAIR);
    }

}