<?php declare(strict_types=1);

use AshleyHardy\Framework\AbstractController;
use AshleyHardy\Framework\Dispatcher;
use AshleyHardy\Framework\Middleware;
use AshleyHardy\Framework\Parameter;
use AshleyHardy\Framework\Request;
use AshleyHardy\Framework\Response;
use AshleyHardy\Framework\Route;
use AshleyHardy\Framework\TestResources\TestBadMiddleware;
use AshleyHardy\Framework\TestResources\TestGoodMiddleware;
use PHPUnit\Framework\TestCase;

class ApiServiceTest extends TestCase
{
    public function testParameterClass(): void
    {
        $parameters = new Parameter([
            'hello' => 'world',
            'whaddup' => 'dawg',
            'colours' => [
                'red',
                'green',
                'blue'
            ],
            'ages' => [
                'ashley' => 26,
                'claire' => 28
            ]
        ]);

        $this->assertEquals(
            'world',
            $parameters->hello,
            'The Parameter class failed to provide the expected value in the simple parameter test.'
        );

        $this->assertEquals(
            26,
            $parameters->ages->ashley,
            'The Parameter class failed to provide the expected value for a nested parameter.'
        );

        $this->assertIsArray(
            $parameters->colours,
            'The Parameter class failed to provide an Array when one was expected.'
        );
    }

    public function testRequestClass(): void
    {
        $_SERVER['REQUEST_URI'] = "/hello/world";

        $_SERVER[Request::PHPUNIT_BODY_KEY] = json_encode([
            'name' => 'Chaka Khan',
            'profession' => 'Music Artist',
            'hobbies' => [
                'Singing',
                'Dancing'
            ]
        ]);

        $request = new Request();

        $this->assertEquals(
            'Chaka Khan',
            $request->params()->name,
            'The request object failed to produce a Parameter class that contained our input data.'
        );

        $this->assertIsArray(
            $request->params()->hobbies,
            'The request object failed to produce a Parameter class that contained our list of hobbies (and return it as an array).'
        );

        $route = $request->getRoute();
        $this->assertEquals(
            'hello',
            $route->getControllerName(),
            'The Route class failed to identify the correct controller name from the test string.'
        );

        $this->assertEquals(
            'world',
            $route->getActionName(),
            'The Route class failed to identify the correct action name from the test string.'
        );
    }

    public function testRouteClassUsesIndexAsADefault(): void
    {
        $_SERVER['REQUEST_URI'] = "controller";
        $route = new Route();
        $this->assertEquals(
            'index',
            $route->getActionName(),
            'The Route class failed to assume the default action of \'index\' when one was not provided.'
        );

        $_SERVER['REQUEST_URI'] = "";
        $route = new Route();
        $this->assertEquals(
            'index',
            $route->getControllerName(),
            'The Route class failed to assume the default controller of \'index\' when one was not provided.'
        );
    }

    public function testDispatcherClass(): void
    {
        $namespace = 'AshleyHardy\\Framework\\TestResources';
        Dispatcher::addNamespace($namespace);

        $this->assertEquals(
            $namespace,
            Dispatcher::getNamespaces()[0],
            'The Dispatcher class failed to statically store available controller namespaces.'
        );

        $_SERVER['REQUEST_URI'] = "test/index";

        $dispatcher = new Dispatcher(new Request());
        
        $this->assertTrue(
            $dispatcher->validate(),
            'The dispatcher failed to validate a known-good route.'
        );

        /** @var Response */
        $response = $dispatcher->dispatch();
        $this->assertInstanceOf(
            Response::class,
            $response,
            'The dispatcher failed to provide a Response object.'
        );

        $responseAsArray = $response->toArray();
        $this->assertEquals(
            200,
            $responseAsArray['code'],
            'The response (as array) did not provide a response code.'
        );
    }

    public function testMiddleware(): void
    {
        Middleware::register(TestGoodMiddleware::class);
        $response = Middleware::checkMiddleware(new Request());

        $this->assertNull(
            $response, 
            'The GoodMiddleware check should validate and return a null response from checkMiddleware indicating the request is OK. Middleware: ' . implode(', ', Middleware::getRegistered())
        );

        Middleware::reset();

        Middleware::register(TestBadMiddleware::class);
        $response = Middleware::checkMiddleware(new Request());

        $this->assertInstanceOf(
            Response::class, 
            $response,
            'The BadMiddleware check should not validate and should return a Response object for rendering, indicating the request must exit here. Middleware: ' . implode(', ', Middleware::getRegistered())
        );
    }

    public function testMethodAccepts(): void
    {
        $namespace = 'AshleyHardy\\Framework\\TestResources';
        Dispatcher::addNamespace($namespace);

        $getAcceptedActions = function(): array {
            return (new Dispatcher(new Request()))->getAcceptedMethodsForAction();
        };

        $runTestFor = function(string $method, string $uri, bool $not = false) use ($getAcceptedActions) {
            $_SERVER['REQUEST_METHOD'] = $method;
            $_SERVER['REQUEST_URI'] = $uri;

            $testMethod = 'assertContains';
            if($not) $testMethod = 'assertNotContains';

            $this->$testMethod(
                $method,
                $getAcceptedActions()
            );
        };

        $runTestFor('POST', 'test/accepts-post');
        $runTestFor('PUT', 'test/accepts-post-and-put');
        $runTestFor('POST', 'test/accepts-post-and-put');
        $runTestFor('GET', 'test/accepts-nothing', true);
    }
}