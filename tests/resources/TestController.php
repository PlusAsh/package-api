<?php declare(strict_types=1);

namespace AshleyHardy\JsonApi\TestResources;

use AshleyHardy\JsonApi\AbstractController;
use AshleyHardy\JsonApi\Response;
use AshleyHardy\JsonApi\Attribute\Method;

class TestController extends AbstractController
{
    #[Method('GET')]
    public function index()
    {
        return Response::respond([
            'hello' => 'world'
        ]);
    }

    #[Method('POST')]
    public function acceptsPost(): Response
    {
        return Response::message('I accept only POST');
    }

    #[Method('POST', 'PUT')]
    public function acceptsPostAndPut(): Response
    {
        return Response::message('I accept POST and PUT');
    }

    public function acceptsNothing(): Response
    {
        return Response::message('Accepts nothing.');
    }
}