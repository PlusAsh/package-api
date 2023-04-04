<?php declare(strict_types=1);

namespace AshleyHardy\JsonApi\TestResources;

use AshleyHardy\JsonApi\AbstractMiddleware;
use AshleyHardy\JsonApi\Response;

class TestGoodMiddleware extends AbstractMiddleware
{
    public function run(): bool
    {
        return true;
    }

    public function rejection(): Response
    {
        return Response::message('The GoodMiddleware class said no. (Impossible, but still)', 200);
    }
}