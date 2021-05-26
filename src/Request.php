<?php 

declare(strict_types=1);

namespace App;

use PDO;

class Request{
    private array $get = [];
    private array $post = [];

    function __construct(?array $get, ?array $post){
        $this->get = $get;
        $this->post = $post;
    }

    public function getRequestGet(string $name, $default = null){
        return $this->get[$name] ?? $default;
    }

    public function getRequestPost(string $name, $default = null){
        return $this->post[$name] ?? $default;
    }
}