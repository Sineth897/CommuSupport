<?php

namespace app\tests\unit;

use app\core\SMS;
use app\models\userModel;
use PHPUnit\Framework\TestCase;

class test_Application extends TestCase
{
    private \app\core\Application $application;

    private \app\models\userModel $userModel;

    private array $config = [];

    protected function setUp() : void {

        if(session_id() !== "") session_destroy();
        session_start();

        $this->config = [
            'userClass' => userModel::class,
            "db" => [
                "dsn" => '',
                "user" => '',
                "password" => ''
            ],
            "root" => [
                "username"  =>  '',
                "password" => ''
            ],
            "sms" => [
                'id' => '',
                'pw' => '',
            ],
        ];

        $this->application = new \app\core\Application(dirname(__DIR__), $this->config);

        $this->application->request = $this->createMock(\app\core\Request::class);
        $this->application->response = $this->createMock(\app\core\Response::class);
        $this->application->cookie = $this->createMock(\app\core\Cookie::class);
        $this->application->sms = $this->createMock(SMS::class);
        $this->application->database = $this->createMock(\app\core\Database::class);

        $this->userModel = $this->createMock(userModel::class);



    }

    /**
     * @param string $primaryKey
     * @param string $primaryKeyValue
     * @param string $username
     * @param string $userType
     * @return void
     * @dataProvider \app\tests\unit\data_provider\data_provider::random_user_details
     */
    public function test_login(string $primaryKey, string $primaryKeyValue, string $username, string $userType): void
    {
        // set user model to give desired inputs in course of normal execution
        $this->userModel->method('primaryKey')->willReturn($primaryKey);
        $this->userModel->method('userType')->willReturn($userType);
        $this->userModel->{$primaryKey} = $primaryKeyValue;
        $this->userModel->username = $username;

        // login function set the session with the details of the user
        $this->assertTrue($this->application->login($this->userModel));

    }



}