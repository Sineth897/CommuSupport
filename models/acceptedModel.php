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
        return ["acceptedID","requestID","acceptedBy","acceptedDate","status","postedBy","approval","approvedDate","item","amount","address","urgency","postedDate","expDate","notes",];
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

    public function saveAcceptedRequest(): bool {
        $this->acceptedID = substr(uniqid('accepted',true),0,23);
        if($_SESSION['userType'] == 'donor')
            $this->acceptedBy = $_SESSION['user'];
        else {
            $CC = logisticModel::getModel(['employeeID' => $_SESSION['user']]);
            $this->acceptedBy = $CC->ccID;
        }

        $this->acceptedDate = date('Y-m-d');
        $this->status = "Accepted";
        return $this->save();
    }
}