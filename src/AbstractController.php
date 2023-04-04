<?php declare(strict_types=1);

namespace AshleyHardy\JsonApi;

abstract class AbstractController
{
    protected Request $request;

    final public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function preDispatch(): void
    {

    }

    public function postDispatch(): void
    {

    }
}