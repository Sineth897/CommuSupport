<?php

namespace app\tests\unit;

use app\core\SMS;
use Mockery;
use app\models\userModel;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;

class test_Application extends TestCase
{
    private \app\core\Application $application;

    private \app\models\userModel $userModel;

    /**
     * @throws Exception
     */
    protected function setUp() : void {

        $config = [
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

        $this->application = new \app\core\Application(dirname(__DIR__), $config);

        $this->application->request = $this->createMock(\app\core\Request::class);
        $this->application->response = $this->createMock(\app\core\Response::class);
        $this->application->cookie = $this->createMock(\app\core\Cookie::class);
        $this->application->sms = $this->createMock(SMS::class);
        $this->application->database = $this->createMock(\app\core\Database::class);

        $this->userModel = $this->createMock(\app\models\userModel::class);

    }

    /**
     * @param string $primaryKey
     * @param string $primaryKeyValue
     * @param string $username
     * @param string $userType
     * @return void
     * @dataProvider \app\tests\unit\data_provider\data_provider::random_user_details
     */
    public function test_login_and_logout(string $primaryKey, string $primaryKeyValue, string $username, string $userType): void
    {

        // set user model to give desired inputs in course of normal execution
        $this->userModel->expects($this->once())->method('primaryKey')->willReturn($primaryKey);
        $this->userModel->expects($this->once())->method('userType')->willReturn($userType);
        $this->userModel->{$primaryKey} = $primaryKeyValue;
        $this->userModel->username = $username;

        // login function set the session with the details of the user
        $this->assertTrue($this->application->login($this->userModel));

//        print_r($_SESSION);

        //check if the session is set with the details of the user
        $this->assertSame($primaryKeyValue, $_SESSION['user']);
        $this->assertSame($userType, $_SESSION['userType']);
        $this->assertSame($username, $_SESSION['username']);

        // logout function unset the session
        $this->application->logout();

        //check if the session is unset
        $this->assertArrayNotHasKey('user', $_SESSION);
        $this->assertArrayNotHasKey('username', $_SESSION);
        $this->assertSame('guest', $_SESSION['userType']);
        $this->assertEmpty($this->application->user);

    }

    /**
     * @return void
     */
    public function test_getSelectorNValidator_when_a_cookie_is_set() : void {

        // setting the cookie value
        $cookie ='84b5c75fc0494f40e0df46bb8638365f:$2y$10$MrNVXptGvRbssaQIYKWR0uwhbUHMCJHJMmsEj2PVZhQ5hD5Dcx.9.';

        $this->application->cookie->expects($this->once())->method('getCookie')->willReturn($cookie)->with($this->equalTo('rememberMe'));

        // calling the function
        [$selector , $validator] = $this->application->getSelectorNValidator();

        // asserting the values
        $this->assertSame('84b5c75fc0494f40e0df46bb8638365f', $selector);
        $this->assertSame('$2y$10$MrNVXptGvRbssaQIYKWR0uwhbUHMCJHJMmsEj2PVZhQ5hD5Dcx.9.', $validator);

    }

    /**
     * @return void
     */
    public function test_getSelectorNValidator_when_a_cookie_is_not_set() : void
    {

        // setting the cookie value
        $cookie = null;

        $this->application->cookie->expects($this->once())->method('getCookie')->willReturn($cookie)->with($this->equalTo('rememberMe'));

        // calling the function
        [$selector, $validator] = $this->application->getSelectorNValidator();

        // asserting the values
        $this->assertSame('', $selector);
        $this->assertSame('', $validator);

    }

}