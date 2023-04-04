<?php declare(strict_types=1);

namespace AshleyHardy\JsonApi;

use Exception;

class SystemResponse
{
    public static function bad404(): Response
    {
        if((new Request())->getRoute()->getControllerName() == 'index') return self::missingHomeResource();
        return Response::message('The requested resource could not be found.', 404);
    }

    public static function bad500(?Exception $e = null): Response
    {
        return Response::message('The server encountered an error processing the request. Message: ' . $e->getMessage(), 500);
    }

    public static function missingHomeResource(): Response
    {
        return Response::message('This API does not provide a \'Home\' Resource.', 400);
    }
}