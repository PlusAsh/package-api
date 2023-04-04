<?php declare(strict_types=1);

namespace AshleyHardy\JsonApi;

abstract class AbstractMiddleware
{
    protected Request $request;

    final public function __construct(Request $request)
    {
        $this->request = $request;
    }

    abstract public function run(): bool;

    abstract public function rejection(): Response;
}