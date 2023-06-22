<?php

namespace MadeiraMadeira\HealthCheck\Infra\Repositories;

use MadeiraMadeira\HealthCheck\Core\Entities\System;
use MadeiraMadeira\HealthCheck\Core\Repositories\HealthCheckRepository;

class HealthCheck implements HealthCheckRepository
{
    private $cache;

    public function __construct($cache)
    {
        $this->cache = $cache;
    }

    public function setDependencies(array $dependencies) 
    {
        $this->cache->set('dependencies', $dependencies);
    }

    public function setDependencyStatus(string $dependencyName, string $status)
    {
        $dependencies = $this->cache->get('dependencies');
        
        foreach ($dependencies as $dependency) {
            if ($dependency['name'] == $dependencyName) {
                $dependency['status'] = $status;
            }
        }

        $this->cache->set('dependencies', $dependencies);
    }

    public function getHealthCheck()
    {
        $basicInfo = $this->cache->get('basic-info');
        $dependencies = $this->cache->get('dependencies');
        $system = $this->getSystemStatus();
        $status = 'Healthy';

        return [
            'name' => $basicInfo['name'],
            'version' => $basicInfo['version'],
            'system' => $system,
            'status' => $status,
            'timestamp' => date('Y-m-d H:i:s.u'),
            'dependencies' => $dependencies
        ];
    }

    public function setHealthCheckBasicInfo($data)
    {
        $this->cache->set('basic-info', $data);
    }

    private function getSystemStatus()
    {
        $system = (new System())
                    ->getCPUInfo()
                    ->getMemoryInfo()
                    ->build();

        return $system;
    }
}