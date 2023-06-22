<?php

namespace MadeiraMadeira\HealthCheck\Core\Entities;

class System
{
    public function toArray()
    {
        return [];
    }

    public function getCPUInfo()
    {
        return $this;
    }

    public function getMemoryInfo()
    {
        return $this;
    }

    public function build()
    {
        return $this->toArray();
    }
}