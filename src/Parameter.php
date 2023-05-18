<?php declare(strict_types=1);

namespace AshleyHardy\Framework;

class Parameter
{
    private array $data;

    public function __construct(array $data)
    {
        foreach($data as $key => $value) {
            if(is_array($value)) {
                $this->data[$key] = new self($value);
            } else {
                $this->data[$key] = $value;
            }
        }
    }

    public function __get(string $key): mixed
    {
        if(!isset($this->data[$key])) return null;
        $value = $this->data[$key];

        if($value instanceof self && $value->isList()) $value = $value->toArray();

        return $value;
    }

    public function toArray(): array
    {
        return $this->data;
    }

    public function isList(): bool
    {
        return array_is_list($this->data);
    }
}