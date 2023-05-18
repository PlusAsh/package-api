<?php declare(strict_types=1);

namespace AshleyHardy\Framework\TestResources;

use AshleyHardy\Framework\AbstractMiddleware;
use AshleyHardy\Framework\Response;

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