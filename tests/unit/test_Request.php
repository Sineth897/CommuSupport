<?php

namespace app\tests\unit;

use app\core\Request;
use PHPUnit\Framework\TestCase;

class test_Request extends TestCase
{

    private Request $request;

    protected function setUp() : void {
        $this->request = new Request();
    }


    public function test_request_constructor_base_URL_initialization() : void {

        // test case to check if the constructor is working properly
        // and the base url is initialized properly

        // Initialize needed classes
        $request = new \app\core\Request();

        // expected outcome
        $expected = [
            '/CommuSupport/' => '/'
        ];

        // assert that the expected outcome is equal to the actual outcome
        $this->assertSame($expected, $request->getBaseURL());

    }

    public function test_request_get_path_for_home_directory() : void {

        // test case to check if the get path function is working properly

        $_SERVER['REQUEST_URI'] = '/CommuSupport/';

        // expected outcome
        $expected = '/';

        // assert that the expected outcome is equal to the actual outcome
        $this->assertEquals($expected, $this->request->getPath());

    }

    public function test_request_get_path_for_home_directory_with_slash() : void {

        // test case to check if the get path function is working properly

        $_SERVER['REQUEST_URI'] = '/CommuSupport//';

        // expected outcome
        $expected = '/';

        // assert that the expected outcome is equal to the actual outcome
        $this->assertSame($expected, $this->request->getPath());

    }

    public function test_request_get_path_for_a_link_deep_inside_the_directory() : void {

        // test case to check if the get path function is working properly

        $_SERVER['REQUEST_URI'] = '/CommuSupport/abc/def/ghi';

        // expected outcome
        $expected = '/abc/def/ghi';

        // assert that the expected outcome is equal to the actual outcome
        $this->assertSame($expected, $this->request->getPath());

    }

    public function test_request_get_path_for_a_link_deep_inside_the_directory_with_slash() : void {

        // test case to check if the get path function is working properly

        $_SERVER['REQUEST_URI'] = '/CommuSupport/abc/def/ghi/';

        // expected outcome
        $expected = '/abc/def/ghi';

        // assert that the expected outcome is equal to the actual outcome
        $this->assertSame($expected, $this->request->getPath());

    }

    public function test_request_get_path_for_a_link_deep_inside_the_directory_with_slash_and_query_string() : void {

        // test case to check if the get path function is working properly

        $_SERVER['REQUEST_URI'] = '/CommuSupport/abc/def/ghi/?test=1';

        // expected outcome
        $expected = '/abc/def/ghi';

        // assert that the expected outcome is equal to the actual outcome
        $this->assertSame($expected, $this->request->getPath());

    }

    public function test_method_function_for_get_method() : void {

        // test case to check if the method function is working properly

        $_SERVER['REQUEST_METHOD'] = 'GET';

        // expected outcome
        $expected = 'get';

        // assert that the expected outcome is equal to the actual outcome
        $this->assertSame($expected, $this->request->method());

    }

    public function test_method_function_for_post_method() : void {

        // test case to check if the method function is working properly

        $_SERVER['REQUEST_METHOD'] = 'POST';

        // expected outcome
        $expected = 'post';

        // assert that the expected outcome is equal to the actual outcome
        $this->assertSame($expected, $this->request->method());

    }

    public function test_is_post_and_is_get_methods_for_get_request() : void {

            // test case to check if the is post method function is working properly

            $_SERVER['REQUEST_METHOD'] = 'GET';

            // expected outcome
            $expected = true;

            // assert that the expected outcome is equal to the actual outcome
            $this->assertSame($expected, $this->request->isGet());
            $this->assertNotSame($expected, $this->request->isPost());

    }

    public function test_is_post_and_is_get_methods_for_post_request() : void {

            // test case to check if the is post method function is working properly

            $_SERVER['REQUEST_METHOD'] = 'POST';

            // expected outcome
            $expected = true;

            // assert that the expected outcome is equal to the actual outcome
            $this->assertNotSame($expected, $this->request->isGet());
            $this->assertSame($expected, $this->request->isPost());

    }

    public function test_getBody_function_for_a_get_request_for_request_with_no_query_params() : void {

            // test case to check if the get body function is working properly

            $_SERVER['REQUEST_METHOD'] = 'GET';

            // expected outcome
            $expected = [];

            // assert that the expected outcome is equal to the actual outcome
            $this->assertSame($expected, $this->request->getBody());

    }


}