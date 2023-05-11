<?php

namespace app\models;
															

use app\core\DbModel;					
class acceptedModel extends DbModel
{
    public string $acceptedID = "";
    public string $requestID = "";
    public string $acceptedBy ="";
    public string $acceptedDate = "";
    public string $status = "";
    public string $postedBy = "";
    public string $approval = "";
    public string $approvedDate = "";
    public string $item= "";
    public string $amount= "";
    public string $address= "";
    public string $urgency= "";
    public string $postedDate= "";
    public string $expDate="";
    public string $notes= "";
    public string $deliveryID= "";
    public string $deliveryStatus= "";

    public function table(): string
    {
        return "acceptedrequest";
    }

    public function attributes(): array
    {
        return ["acceptedID","requestID","acceptedBy","postedBy","approval","approvedDate","item","amount","address","urgency","postedDate","expDate","notes",'deliveryID'];
    }

    public function primaryKey(): string
    {
        return "acceptedID";
    }

    public function rules(): array
    {
        return [

        ];
    }

    public function getDataFromThePostedRequest(requestModel $requestModel) {
        $this->requestID = $requestModel->requestID;
        $this->postedBy = $requestModel->postedBy;
        $this->approval = $requestModel->approval;
        $this->approvedDate = $requestModel->approvedDate;
        $this->item = $requestModel->item;
        $this->amount = $requestModel->amount;
        $this->address = $requestModel->address;
        $this->urgency = $requestModel->urgency;
        $this->postedDate = $requestModel->postedDate;
        $this->expDate = $requestModel->expDate;
        $this->notes = $requestModel->notes;
    }


//    get requests accepted by user (Donor or CC)
    public function getAcceptedRequests(string $ID) : bool|array
    {
        $sql = "SELECT * FROM acceptedrequest a 
                        INNER JOIN subcategory s ON a.item = s.subcategoryID 
                        WHERE a.acceptedBy = :ID";

        $stmt = self::prepare($sql);
        $stmt->bindValue(':ID', $ID);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    static public function getAcceptedRequestsByUserID(string $acceptedBy) : bool|array {
        $sql = "SELECT * FROM acceptedrequest a 
                        INNER JOIN subcategory s ON a.item = s.subcategoryID 
                        INNER JOIN category c on s.categoryID = c.categoryID 
                        WHERE a.acceptedBy = :ID";

        $stmt = self::prepare($sql);
        $stmt->bindValue(':ID', $acceptedBy);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    static public function getCompletedRequestsUnderCC(string $ccID) : bool|array {

        $sql = "SELECT *,CONCAT(SUM(r.amount),' ',s.scale) AS amount,
                            COUNT(r.requestID) AS users FROM acceptedrequest r 
                            INNER JOIN subcategory s ON r.item = s.subcategoryID 
                            INNER JOIN category c ON s.categoryID = c.categoryID 
                            WHERE r.postedBy IN (SELECT doneeID FROM donee WHERE ccID = :ID) AND r.deliveryStatus = 'Completed' 
                            GROUP BY r.requestID";

        $stmt = self::prepare($sql);
        $stmt->bindValue(':ID', $ccID);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * @param string $ccID
     * @param array $filters
     * @param array $sort
     * @return array
     */
    static public function getCompletedReqeustsUnderCCFilteredAndSorted(string $ccID, array $filters, array $sort) : array {
        $sql = "SELECT *,CONCAT(SUM(r.amount),' ',s.scale) AS amount,
                            COUNT(r.requestID) AS users FROM acceptedrequest r 
                            INNER JOIN subcategory s ON r.item = s.subcategoryID 
                            INNER JOIN category c ON s.categoryID = c.categoryID 
                            WHERE r.postedBy IN (SELECT doneeID FROM donee WHERE ccID = :ID) AND r.deliveryStatus = 'Completed'";

        if(!empty($filters)) {
            $sql .= " AND r.item = '{$filters['item']}'";
        }

        $sql .= "GROUP BY r.requestID";

        if(!empty($sort['DESC'])) {
            $sql .= " ORDER BY " . implode(",",$sort['DESC']) . ' DESC';
        }

        $stmt = self::prepare($sql);
        $stmt->bindValue(':ID', $ccID);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);

    }

 }