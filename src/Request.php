<?php

declare(strict_types=1);

namespace App;

//class for keep requestem data - get and post
class Request
{
    private array $get = [];
    private array $post = [];

    //forward value to class property 
    public function __construct(array $get, array $post)
    {
        $this->get = $get;
        $this->post = $post;
    }

    //keep value from get param in class property
    public function getParam(string $name, $default = null)
    {
        return $this->get[$name] ?? $default;
    }

    //keep value from post param in class property
    public function postParam(string $name, $default = null)
    {
        return $this->post[$name] ?? $default;
    }
    
    //set value in class property
    public function setPostParam(string $name, $value = null)
    {
        $this->post[$name] = $value;
        return $this->post[$name];
    }


}
