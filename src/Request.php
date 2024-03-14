<?php

declare(strict_types=1);

namespace App;

class Request
{
    private array $get = [];
    private array $post = [];


    public function __construct(array $get, array $post)
    {
        $this->get = $get;
        $this->post = $post;
    }


    public function getParam(string $name, $default = null)
    {
        return $this->get[$name] ?? $default;
    }


    public function postParam(string $name, $default = null)
    {
        return $this->post[$name] ?? $default;
    }

    public function setPostParam(string $name, $value = null)
    {
        $this->post[$name] = $value;
        return $this->post[$name];
    }


}
