<?php

namespace app\tests\unit;


use app\core\Response;
use PHPUnit\Framework\TestCase;

class test_Response extends TestCase
{
    private Response $response;
    public function setUp(): void
    {
        $this->response = new Response();
        parent::setUp();
    }

    /**
     * @dataProvider \app\tests\unit\data_provider\data_provider::random_data()
     */
    public function test_setData_function($test_data) : void {

        // function call to set data
        $this->response->setData('test');

        // assert that the expected outcome is equal to the actual outcome
        $this->assertEquals('test', $this->response->getData());

    }

    /**
     * @dataProvider \app\tests\unit\data_provider\data_provider::JSON_endcoded_data()
     */
    public function test_setJsonData_function_with_JSON_encoding($test_data, $expected) : void
    {

        // function call to set data
        $this->response->setJsonData($test_data);

        // assert that the expected outcome is equal to the actual outcome
        $this->expectOutputRegex('/' . $expected . '/');
        echo $this->response->getData();

    }

    /**
     * @dataProvider \app\tests\unit\data_provider\data_provider::JSON_endcoded_data()
     */
    public function test_send_function($test_data,$expected) : void {

        // function call to set data
        $this->response->setJsonData($test_data);

        // assert that the expected outcome is equal to the actual outcome
        $this->expectOutputRegex('/' . $expected . '/');
        $this->response->send();

    }
}
