<?php declare(strict_types=1);

namespace AshleyHardy\JsonApi;

use Hardy\PeriodApi\Issues\ApiIssue;

class Route
{
    private string $controllerName;
    private string $actionName;

    public function __construct()
    {
        $urlParts = explode("/", trim($_SERVER['REQUEST_URI'] ?? "", '/'));
        
        $this->controllerName = !empty($urlParts[0]) ? $urlParts[0] : "index";
        $this->actionName = !empty($urlParts[1]) ? $urlParts[1] : "index";
    }

    public function getControllerName(): string
    {
        return $this->controllerName;
    }

    public function getActionName(): string
    {
        return $this->actionName;
    }
}