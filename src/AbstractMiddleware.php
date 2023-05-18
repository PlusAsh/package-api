<?php declare(strict_types=1);

namespace AshleyHardy\Framework;

abstract class AbstractMiddleware
{
    protected Request $request;

    final public function __construct(Request $request)
    {
        $this->request = $request;
    }

    abstract public function run(): bool;

    public function rejection(): Response
    {
        return Response::message('Requested rejected by ' . get_class($this));
    }
}