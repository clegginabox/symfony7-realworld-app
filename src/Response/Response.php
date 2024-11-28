<?php

namespace App\Response;

class Response
{
    public function __construct(private mixed $data, private string $responseClass, private int $responseCode = 200)
    {
    }

    public function getData(): mixed
    {
        return $this->data;
    }

    public function getResponseClass(): string
    {
        return $this->responseClass;
    }

    public function getResponseCode(): int
    {
        return $this->responseCode;
    }
}
