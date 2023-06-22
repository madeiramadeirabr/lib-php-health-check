<?php

namespace MadeiraMadeira\HealthCheck\Infra\Datasources\Memory;

use MadeiraMadeira\HealthCheck\Infra\Datasources\Memory\Interfaces\MemoryInterface;

class HashMapMemory implements MemoryInterface
{
    private $memory = [];

    public function get(string $key) 
    {
        return !empty($this->memory[$key]) ? $this->memory[$key] : null;
    }

    public function set(string $key, $value) 
    {
        $this->memory[$key] = $value;
    }

    public function all() 
    {
        return array_values($this->memory);
    }

    public function clear() 
    {
        $this->memory = [];
    }
}