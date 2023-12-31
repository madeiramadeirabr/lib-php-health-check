<?php

namespace Tests\Mock;

use MadeiraMadeira\HealthCheck\Core\Entities\Status;
use Tests\Mock\DependencyStub;


class HealthCheckStub
{
    public function getBasicInfoStub()
    {
        return [
            'name' => 'stub basic info',
            'version' => '123'
        ];
    }

    public function getStatusHealthyStub()
    {
        return Status::getHealthyStatus();
    }

    public function getStatusUnhealthyStub()
    {
        return Status::getUnhealthyStatus();
    }

    public function getStatusUnavailableStub()
    {
        return Status::getUnavailiableStatus();
    }

    public function getSystemStub()
    {
        return [
            'cpu' => [
                'utilization' => 0.1
            ],
            'memory' => [
                'total' => 1000,
                'used' => 100
            ]
        ];
    }

    public function getDependenciesHealthyStub()
    {
        $dependency = (new DependencyStub())->getDependencyMysqlHealthy();

        return [
            $dependency
        ];
    }

    public function getDependenciesUnhealthyStub()
    {
        $dependency = (new DependencyStub())->getDependencyMysqlUnhealthy();

        return [
            $dependency
        ];
    }

    public function getDependenciesUnavailableStub()
    {
        $dependency = (new DependencyStub())->getDependencyMysqlUnvailable();

        return [
            $dependency
        ];
    }

    public function getHealthCheckStub($dependencies = null)
    {
        $basicInfo = $this->getBasicInfoStub();
        $dependencies = !$dependencies ? $this->getDependenciesHealthyStub() : $dependencies;
        $dependencies[0] = $dependencies[0]->toArray();

        $healthCheckStub = [
            'name' => $basicInfo['name'],
            'version' => $basicInfo['version'],
            'status' => $this->getStatusHealthyStub(),
            'system' => $this->getSystemStub(),
            'dependencies' => $dependencies,
            'timestamp' => $this->getTimestamp() 
        ];

        return $healthCheckStub;
    }

    public function getHealthCheckUnhealthyStub($dependencies = null)
    {
        $basicInfo = $this->getBasicInfoStub();
        $dependencies = !$dependencies ? $this->getDependenciesUnhealthyStub() : $dependencies;
        $dependencies[0] = $dependencies[0]->toArray();

        $healthCheckStub = [
            'name' => $basicInfo['name'],
            'version' => $basicInfo['version'],
            'status' => $this->getStatusUnhealthyStub(),
            'system' => $this->getSystemStub(),
            'dependencies' => $dependencies,
            'timestamp' => $this->getTimestamp() 
        ];

        return $healthCheckStub;
    }

    public function getHealthCheckUnavailableStub($dependencies = null)
    {
        $basicInfo = $this->getBasicInfoStub();
        $dependencies = !$dependencies ? $this->getDependenciesUnavailableStub() : $dependencies;
        $dependencies[0] = $dependencies[0]->toArray();

        $healthCheckStub = [
            'name' => $basicInfo['name'],
            'version' => $basicInfo['version'],
            'status' => $this->getStatusUnavailableStub(),
            'system' => $this->getSystemStub(),
            'dependencies' => $dependencies,
            'timestamp' =>  $this->getTimestamp()
        ];

        return $healthCheckStub;
    }

    public function getInvalidBasicInfo()
    {
        return [
            'name' => '',
            'version' => ''
        ];
    }

    public function getTimestamp()
    {
        return '2022-09-27 18:00:00.000';
    }
}