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

    /**
     * @return void
     */
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

    /**
     * @return void
     */
    public function test_request_get_path_for_home_directory() : void {

        // test case to check if the get path function is working properly

        $_SERVER['REQUEST_URI'] = '/CommuSupport/';

        // expected outcome
        $expected = '/';

        // assert that the expected outcome is equal to the actual outcome
        $this->assertEquals($expected, $this->request->getPath());

    }

    /**
     * @return void
     */
    public function test_request_get_path_for_home_directory_with_slash() : void {

        // test case to check if the get path function is working properly

        $_SERVER['REQUEST_URI'] = '/CommuSupport//';

        // expected outcome
        $expected = '/';

        // assert that the expected outcome is equal to the actual outcome
        $this->assertSame($expected, $this->request->getPath());

    }

    /**
     * @return void
     */
    public function test_request_get_path_for_a_link_deep_inside_the_directory() : void {

        // test case to check if the get path function is working properly

        $_SERVER['REQUEST_URI'] = '/CommuSupport/abc/def/ghi';

        // expected outcome
        $expected = '/abc/def/ghi';

        // assert that the expected outcome is equal to the actual outcome
        $this->assertSame($expected, $this->request->getPath());

    }

    /**
     * @return void
     */
    public function test_request_get_path_for_a_link_deep_inside_the_directory_with_slash() : void {

        // test case to check if the get path function is working properly

        $_SERVER['REQUEST_URI'] = '/CommuSupport/abc/def/ghi/';

        // expected outcome
        $expected = '/abc/def/ghi';

        // assert that the expected outcome is equal to the actual outcome
        $this->assertSame($expected, $this->request->getPath());

    }

    /**
     * @return void
     */
    public function test_request_get_path_for_a_link_deep_inside_the_directory_with_slash_and_query_string() : void {

        // test case to check if the get path function is working properly

        $_SERVER['REQUEST_URI'] = '/CommuSupport/abc/def/ghi/?test=1';

        // expected outcome
        $expected = '/abc/def/ghi';

        // assert that the expected outcome is equal to the actual outcome
        $this->assertSame($expected, $this->request->getPath());

    }

    /**
     * @return void
     */
    public function test_method_function_for_get_method() : void {

        // test case to check if the method function is working properly

        $_SERVER['REQUEST_METHOD'] = 'GET';

        // expected outcome
        $expected = 'get';

        // assert that the expected outcome is equal to the actual outcome
        $this->assertSame($expected, $this->request->method());

    }

    /**
     * @return void
     */
    public function test_method_function_for_post_method() : void {

        // test case to check if the method function is working properly

        $_SERVER['REQUEST_METHOD'] = 'POST';

        // expected outcome
        $expected = 'post';

        // assert that the expected outcome is equal to the actual outcome
        $this->assertSame($expected, $this->request->method());

    }

    /**
     * @return void
     */
    public function test_is_post_and_is_get_methods_for_get_request() : void {

            // test case to check if the is post method function is working properly

            $_SERVER['REQUEST_METHOD'] = 'GET';

            // expected outcome
            $expected = true;

            // assert that the expected outcome is equal to the actual outcome
            $this->assertSame($expected, $this->request->isGet());
            $this->assertNotSame($expected, $this->request->isPost());

    }

    /**
     * @return void
     */
    public function test_is_post_and_is_get_methods_for_post_request() : void {

            // test case to check if the is post method function is working properly

            $_SERVER['REQUEST_METHOD'] = 'POST';

            // expected outcome
            $expected = true;

            // assert that the expected outcome is equal to the actual outcome
            $this->assertNotSame($expected, $this->request->isGet());
            $this->assertSame($expected, $this->request->isPost());

    }

    /**
     * @return void
     */
    public function test_getBody_function_for_a_get_request_for_request_with_no_query_params() : void {

            // test case to check if the get body function is working properly

            $_SERVER['REQUEST_METHOD'] = 'GET';

            // expected outcome
            $expected = [];

            // assert that the expected outcome is equal to the actual outcome
            $this->assertSame($expected, $this->request->getBody());

    }

    /**
     * @param string $link
     * @param string $usertype
     * @return void
     * @dataProvider \app\tests\unit\data_provider\data_provider::random_user_details_with_valid_link_to_their_views
     */
    public function test_getUser_function_with_valid_links_respective_usertype(string $link, string $usertype) : void {

        // test case to check if the get user function is working properly

        $_SERVER['REQUEST_URI'] = $link;

        // expected outcome
        $expected = $usertype;

        // assert that the expected outcome is equal to the actual outcome
        $this->assertSame($expected, $this->request->getUser());

    }

    /**
     * @param string $link
     * @param string $usertype
     * @return void
     * @dataProvider \app\tests\unit\data_provider\data_provider::random_user_details_with_invalid_link_to_their_views
     */
    public function test_getUser_function_with_invalid_links_respective_usertype(string $link, string $usertype) : void
    {

        // test case to check if the get user function is working properly

        $_SERVER['REQUEST_URI'] = $link;

        // expected outcome
        $expected = $usertype;

        // assert that the expected outcome is equal to the actual outcome
        $this->assertNotSame($expected, $this->request->getUser());
    }

    /**
     * @param array $arr
     * @return void
     * @dataProvider \app\tests\unit\data_provider\data_provider::random_arrays
     */
    public function test_getBody_function_for_a_get_request(array $arr) : void {

                // test case to check if the get body function is working properly

                $_SERVER['REQUEST_METHOD'] = 'GET';
                foreach ($arr as $k => $v) {
                    $_GET[$k] = $v;
                }

                // assert that the expected outcome is equal to the actual outcome
                $EXPECTED = $this->request->getBody();
                foreach ($arr as $k => $v) {
                    $this->assertEquals($v, $EXPECTED[$k]);
                }

    }

    /**
     * @param array $arr
     * @return void
     * @dataProvider \app\tests\unit\data_provider\data_provider::random_arrays
     */
    public function test_getBody_function_for_a_post_request(array $arr) : void
    {

        // test case to check if the get body function is working properly

        $_SERVER['REQUEST_METHOD'] = 'POST';
        foreach ($arr as $k => $v) {
            $_POST[$k] = $v;
        }

        // assert that the expected outcome is equal to the actual outcome
        $EXPECTED = $this->request->getBody();
        foreach ($arr as $k => $v) {
            $this->assertEquals($v, $EXPECTED[$k]);
        }

    }

}