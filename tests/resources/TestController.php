<?php declare(strict_types=1);

namespace AshleyHardy\Framework\TestResources;

use AshleyHardy\Framework\AbstractController;
use AshleyHardy\Framework\Response;
use AshleyHardy\Framework\Attribute\Method;

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