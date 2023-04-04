<?php declare(strict_types=1);

namespace AshleyHardy\JsonApi;

class Request
{
    public const PHPUNIT_BODY_KEY = "phpunit/request-body";

    private Route $route;
    private array $requestJson;
    private string $requestBody;
    private ?array $jsonError;
    private Parameter $parameters;
    private string $method = 'GET';

    public function __construct()
    {
        $this->route = new Route();

        $this->requestBody = $this->fetchRequestBody();

        $this->requestJson = json_decode($this->requestBody, true) ?? [];
        if(json_last_error() && !empty($this->requestBody)) {
            $this->jsonError = [
                'code' => json_last_error(),
                'message' => json_last_error_msg()
            ];
        }

        $this->parameters = new Parameter($this->requestJson);

        $this->method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
    }

    private function fetchRequestBody(): string
    {
        return $_SERVER[self::PHPUNIT_BODY_KEY] ?? file_get_contents("php://input");
    }

    public function getRequestJson(): array
    {
        return $requestJson ?? [];
    }

    public function getRequestBody(): string
    {
        return $this->requestBody;
    }

    public function isJsonError(): bool
    {
        return $this->jsonError !== null;
    }

    public function getJsonError(): array
    {
        return $this->jsonError;
    }

    public function params(): Parameter
    {
        return $this->parameters;
    }

    public function getRoute(): Route
    {
        return $this->route;
    }

    public function getRequestMethod(): string
    {
        return $this->method;
    }
}