<?php

namespace Tests\Mock;

use MadeiraMadeira\HealthCheck\Core\Entities\Dependency;
use MadeiraMadeira\HealthCheck\Core\Entities\Kind;
use MadeiraMadeira\HealthCheck\Core\Entities\Status;

class DependencyStub
{
    public function getDependencyMysqlHealthy()
    {
        return new Dependency([
            'name' => 'dependency 1',
            'kind' => Kind::getMysqlKind(),
            'optional' => false,
            'internal' => true,
            'status' => Status::getHealthyStatus()
        ]);
    }

    public function getDependencyMysqlUnhealthy()
    {
        return new Dependency([
            'name' => 'dependency 1',
            'kind' => Kind::getMysqlKind(),
            'optional' => false,
            'internal' => true,
            'status' => Status::getUnhealthyStatus()
        ]);
    }

    public function getDependencyMysqlUnvailable()
    {
        return new Dependency([
            'name' => 'dependency 1',
            'kind' => Kind::getMysqlKind(),
            'optional' => false,
            'internal' => true,
            'status' => Status::getUnavailiableStatus()
        ]);
    }

    public function getDependencyOptionalMysqlUnvailable()
    {
        return new Dependency([
            'name' => 'dependency 1',
            'kind' => Kind::getMysqlKind(),
            'optional' => true,
            'internal' => true,
            'status' => Status::getUnavailiableStatus()
        ]);
    }

    public function getDependenciesUnhealthy()
    {
        $dependencies = [
            $this->getDependencyMysqlUnhealthy()
        ];
        
        return $dependencies;
    }
    
    public function getDependenciesUnavailable()
    {
        $dependencies = [
            $this->getDependencyMysqlUnvailable()
        ];

        return $dependencies;

    }
    public function getDependenciesOptionalUnavailable()
    {
        $dependencies = [
            $this->getDependencyOptionalMysqlUnvailable()
        ];

        return $dependencies;
    }

    public function getDependenciesHealthy()
    {
        $dependencies = [
            $this->getDependencyMysqlHealthy(),
        ];

        return $dependencies;
    }

    public function getValidDependencyData()
    {
        return [
            'dependencyName' => 'dependency',
            'status' => Status::getHealthyStatus()
        ];
    }

    public function getInvalidDependencyData()
    {
        return [
            'dependencyName' => 'dependency',
            'status' => 'Invalid status'
        ];
    }
}