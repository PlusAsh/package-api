<?php declare(strict_types=1);

namespace AshleyHardy\JsonApi;

class Response
{
    public static function respond(array $data, int $code = 200): static
    {
        return new static($data, $code);
    }

    public static function message(string $message, int $code = 200): static
    {
        return self::respond(['message' => $message], $code);
    }

    private array $data;
    private int $code;

    public function __construct(array $data, int $code)
    {
        $this->data = $data;
        $this->code = $code;
    }

    public function toJson(): string
    {
        return json_encode($this->data);
    }

    public function toArray(): array
    {
        return [
            'code' => $this->code,
            'data' => $this->data
        ];
    }

    public function render(): void
    {
        http_response_code($this->code);
        echo $this->toJson();
        exit;
    }
}