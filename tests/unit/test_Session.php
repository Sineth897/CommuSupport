<?php

namespace app\tests\unit;

use app\core\Session;
use PHPUnit\Framework\TestCase;

class test_Session extends TestCase
{
    private Session $session;

    protected function setUp() : void {
        $this->session = new Session();
    }

    /**
     * @var string $key
     * @var mixed $value
     * @return void
     * @dataProvider \app\tests\unit\data_provider\data_provider::random_key_value_pair()
     */
    public function test_session_set_and_get_functions(string $key, $value) : void {

        // test case to check if the set and get functions are working properly

        // store data on the session
        $this->session->set($key, $value);

        // assert that value is stores in the session
        $this->assertNotEmpty($this->session->get($key));
        $this->assertEquals($value, $this->session->get($key));

    }

    /**
     * @var string $key
     * @var mixed $value
     * @return void
     * @dataProvider \app\tests\unit\data_provider\data_provider::random_key_value_pair()
     */
    public function test_remove_function(string $key,$value) : void {

        // test case to check if the remove function is working properly

        // store data on the session
        $this->session->set($key, $value);

        // assert that value is stored in the session
        $this->assertNotEmpty($this->session->get($key));

        // remove data from the session
        $this->session->remove($key);

        // assert that value is removed from the session
        $this->assertEmpty($this->session->get($key));

    }

    /**
     * @var string $key
     * @var mixed $value
     * @return void
     * @dataProvider \app\tests\unit\data_provider\data_provider::random_key_value_pair()
     */
    public function test_setFlash_and_getFlash_functions(string $key, $value) : void {

            // test case to check if the setFlash and getFlash functions are working properly

            // store data on the session
            $this->session->setFlash($key, $value);

            // assert that value is stored in the session
            $this->assertNotEmpty($this->session->getFlash($key));

            // assert that value is removed from the session
            $this->assertEquals($value, $this->session->getFlash($key));

    }

}