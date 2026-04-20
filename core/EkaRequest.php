<?php
namespace EkaCore;

class EkaRequest
{
    private array $params;
    private array $post;
    private array $get;

    public function __construct(array $params = [], array $post = [], array $get = [])
    {
        $this->params = $params;
        $this->post = $post;
        $this->get = $get;
    }

    public function getParam(string $key, mixed $default = null): mixed
    {
        return $this->params[$key] ?? $default;
    }

    public function input(string $key, mixed $default = null): mixed
    {
        return $this->post[$key] ?? $this->get[$key] ?? $default;
    }

    public function all(): array
    {
        return array_merge($this->get, $this->post);
    }
}
