<?php declare(strict_types=1);

namespace AshleyHardy\JsonApi\TestResources;

use AshleyHardy\JsonApi\AbstractMiddleware;
use AshleyHardy\JsonApi\Response;

class TestBadMiddleware extends AbstractMiddleware
{
    public function run(): bool
    {
        return false;
    }

    public function rejection(): Response
    {
        return Response::message('The BadMiddleware class said no. (which - coincidentally - is good news)', 400);
    }
}