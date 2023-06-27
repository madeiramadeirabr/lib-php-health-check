<?php

namespace MadeiraMadeira\HealthCheck\Core\Repositories;

interface HealthCheckRepository 
{
    public function setDependencies(array $dependencies);

    public function setDependencyStatus(string $dependencyName, string $status);

    public function getHealthCheck();

    public function setHealthCheckBasicInfo(array $data);
}