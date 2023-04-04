<?php

namespace AshleyHardy\JsonApi;

use AshleyHardy\Utilities\Str;

class Dispatcher 
{
    private static $controllerNamespaces = [];

    public static function addNamespace(string $namespace): void
    {
        self::$controllerNamespaces[] = rtrim($namespace, "/");
    }

    public static function addNamespaces(array $namespaces): void
    {
        foreach($namespaces as $namespace) {
            self::addNamespace($namespace);
        }
    }

    public static function getNamespaces(): array
    {
        return self::$controllerNamespaces;
    }

    private Request $request;
    private Route $route;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->route = $this->request->getRoute();
    }

    public function validate(): bool
    {
        $controllerClass = $this->getControllerClass();
        if($controllerClass == null) return false;

        $methodName = $this->getMethodName();
        return method_exists($controllerClass, $methodName);
    }

    private function getControllerClass(): ?string
    {
        $controllerName = $this->getControllerName();

        foreach(self::$controllerNamespaces as $namespace) {
            $class = $namespace . "\\" . $controllerName;
            if(class_exists($class)) return $class;
        }

        return null;
    }

    private function getControllerInstance(): AbstractController
    {
        $class = $this->getControllerClass();
        return new $class($this->request);
    }

    private function getControllerName(): string
    {
        return Str::kebabToPascal($this->route->getControllerName()) . 'Controller';
    }

    private function getMethodName(): string
    {
        return Str::kebabToCamel($this->route->getActionName());
    }

    public function dispatch()
    {
        $instance = $this->getControllerInstance();
        $method = $this->getMethodName();
        
        $instance->preDispatch();
        $response = $instance->$method();
        $instance->postDispatch();

        return $response;
    }
}