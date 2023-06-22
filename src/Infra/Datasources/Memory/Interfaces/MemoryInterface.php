<?php

namespace MadeiraMadeira\HealthCheck\Infra\Datasources\Memory\Interfaces;

interface MemoryInterface
{
    public function get(string $key);
    public function set(string $key, $value);
    public function all();
    public function clear();
}