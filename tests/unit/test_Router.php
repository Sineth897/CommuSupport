<?php declare(strict_types=1);

namespace app\tests\unit;

use app\core\exceptions\methodNotFound;
use app\core\exceptions\notFoundException;
use app\core\Request;
use app\core\Response;
use app\core\Router;
use Exception;
use PHPUnit\Framework\TestCase;

class test_Router extends TestCase
{
    private Request $request;
    private Response $response;
    private Router $router;

    protected function setUp() : void {
        $this->request = new Request();
        $this->response = new Response();
        $this->router = new Router($this->request, $this->response);
    }

    public function test_router_constructor_whether_routes_are_empty() : void {

        // test case to check if the routes array is empty at initialization

        $router = new Router($this->request, $this->response);

        // assert that the expected outcome is equal to the actual outcome
        $this->assertEmpty($router->getRoutes());

    }


    public function test_router_get_function() : void {

        // test case to check if the get function is working properly

        // function call
        $this->router->get('/path/test/function', function() {
            return "test";
        });

        // expected outcome
        $expected =  [
            'get' => [
                '/path/test/function' => function() {
                    return "test";
                }
            ]
        ];

        // assert that the expected outcome is equal to the actual outcome
        $this->assertNotEmpty($this->router->getRoutes());
        $this->assertEquals($expected, $this->router->getRoutes());

    }


    public function test_router_post_function() : void {

            // test case to check if the post function is working properly

            // function call
            $this->router->post('/path/test/function', function() {
                return "test";
            });

            // expected outcome
            $expected =  [
                'post' => [
                    '/path/test/function' => function() {
                        return "test";
                    }
                ]
            ];

            // assert that the expected outcome is equal to the actual outcome
            $this->assertNotEmpty($this->router->getRoutes());
            $this->assertEquals($expected, $this->router->getRoutes());

    }

    /**
     * @throws notFoundException
     * @throws methodNotFound
     */
    public function test_route_resolve_function_for_exiting_links_where_call_back_is_a_string() : void {

        // test case to check if the resolve function is working properly for existing links

        // initialize super globals
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '/path/test/function';

        // function call
        $this->router->get('/path/test/function', "link to test function");

        // expected outcome
        $expected =  [
            'get' => [
                '/path/test/function' => "link to test function"
            ]
        ];

        // assert that the expected outcome is equal to the actual outcome
        $this->assertNotEmpty($this->router->getRoutes());
        $this->assertEquals($expected, $this->router->getRoutes());

        // assert that the expected outcome is equal to the actual outcome
        $this->assertSame("link to test function", $this->router->resolve());


    }

    /**
     * @throws notFoundException
     * @throws methodNotFound
     */
    public function test_route_resolve_function_for_exiting_links_where_call_back_is_a_function() : void {

        // test case to check if the resolve function is working properly for existing links

        // initialize super globals
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '/path/test/function';

        // function call
        $this->router->get('/path/test/function', function() {
            return "link to test function";
        });

        // expected outcome
        $expected =  [
            'get' => [
                '/path/test/function' => function() {
                    return "link to test function";
                }
            ]
        ];

        // assert that the expected outcome is equal to the actual outcome
        $this->assertNotEmpty($this->router->getRoutes());
        $this->assertEquals($expected, $this->router->getRoutes());

        // assert that the expected outcome is equal to the actual outcome
        $this->assertSame("link to test function", $this->router->resolve());

    }

    /**
     * @throws notFoundException
     * @throws methodNotFound
     */
    public function test_route_resolve_function_for_existing_links_where_call_back_is_not_a_string_or_function() : void {

            // test case to check if the resolve function is working properly for existing links

            // initialize super globals
            $_SERVER['REQUEST_METHOD'] = 'GET';
            $_SERVER['REQUEST_URI'] = '/path/test/function';

            // function call
            $this->router->get('/path/test/function', 123);

            // expected outcome
            $expected =  [
                'get' => [
                    '/path/test/function' => 123
                ]
            ];

            // assert that the expected outcome is equal to the actual outcome
            $this->assertNotEmpty($this->router->getRoutes());
            $this->assertSame($expected, $this->router->getRoutes());

            // assert that the expected outcome is equal to the actual outcome
            $this->expectException(Exception::class);
            $this->router->resolve();
    }

    /**
     * @throws notFoundException
     */
    public function test_route_resolve_function_for_non_exiting_links() : void
    {

        // test case to check if the resolve function is working properly for non-existing links

        // initialize super globals
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '/path/test/function';

        // function call
        $this->router->get('/path/test/function', 1);
        $this->router->post('/path/test/function', ["test",1]);

        // expected outcome
        $expected = [
            'get' => [
                '/path/test/function' => 1
            ],
            'post' => [
                '/path/test/function' => ["test",1]
            ]
        ];

        // assert that the expected outcome is equal to the actual outcome
        $this->assertNotEmpty($this->router->getRoutes());
        $this->assertSame($expected, $this->router->getRoutes());

        // initialize super globals
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '/path/test/function';

        // assert for get request where callback is an integer
        $this->expectException(methodNotFound::class);
        $this->router->resolve();

        // initialize super globals
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_SERVER['REQUEST_URI'] = '/path/test/function';

        // assert for post request where callback is an array
        $this->expectException(methodNotFound::class);
        $this->router->resolve();

    }

    /**
     * @param $test_data
     * @param string $expected
     * @return void
     * @dataProvider \app\tests\unit\data_provider\data_provider::JSON_endcoded_data()
     */
    public function test_sendData_function($test_data, string $expected) : void {

        // test case to check if the sendData function is working properly
        // here data is sent as a json object

        //output is matched with regex because of preceding and trailing ' character
        $this->expectOutputRegex('/' . $expected .'/');

        // function call
        $this->router->sendData($test_data);

    }

}