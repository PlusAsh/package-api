<?php declare(strict_types=1);

namespace AshleyHardy\JsonApi;

use Exception;
use AshleyHardy\JsonApi\Dispatcher;
use AshleyHardy\JsonApi\Request;
use AshleyHardy\JsonApi\Response;
use AshleyHardy\JsonApi\SystemResponse;
use AshleyHardy\JsonApi\Middleware;

class Api
{
    public static function run(): void
    {
        try {
            $request = new Request();
            $middlewareResponse = Middleware::checkMiddleware($request);
            if($middlewareResponse instanceof Response) {
                $middlewareResponse->render();
            }

            $dispatcher = new Dispatcher($request);

            $response = $dispatcher->validate() ? $dispatcher->dispatch() : SystemResponse::bad404();
        } catch(Exception $issue) {
            $response = SystemResponse::bad500($issue);
        }

        $response->render();
    }
}