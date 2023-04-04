<?php declare(strict_types=1);

namespace AshleyHardy\JsonApi\TestResources;

use AshleyHardy\JsonApi\AbstractController;
use AshleyHardy\JsonApi\Response;

class TestController extends AbstractController
{
    public function index()
    {
        return Response::respond([
            'hello' => 'world'
        ]);
    }
}