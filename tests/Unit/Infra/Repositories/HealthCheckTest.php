<?php

namespace Tests\Unit\Infra\Repositories;

use PHPUnit\Framework\TestCase;
use MadeiraMadeira\HealthCheck\Infra\Repositories\HealthCheck;
use MadeiraMadeira\HealthCheck\Infra\Datasources\Memory\Interfaces\MemoryInterface;
use Tests\Mock\DependencyStub;
use Tests\Mock\HealthCheckStub;

class HealthCheckTest extends TestCase
{
    public function getMemoryMock()
    {
        $builder = $this->getMockBuilder(MemoryInterface::class)
                    ->onlyMethods([
                        'set',
                        'get',
                        'all',
                        'clear'
                    ]);
        $mock = $builder->getMock();

        return $mock;

    }

    public function testSetDependencies()
    {
        $mock = $this->getMemoryMock();
        $healthCheck = new HealthCheck($mock);
        
        $dependencies = (new DependencyStub())->getDependenciesHealthy();

        $mock->expects($this->once())
            ->method('set')
            ->with(
                'dependencies',
                $dependencies
            );

        
        $healthCheck->setDependencies($dependencies);
    }

    public function testSetDependencyStatus()
    {
        $mock = $this->getMemoryMock();
        $healthCheck = new HealthCheck($mock);


        $dependencies = (new DependencyStub())->getDependenciesHealthy();

        $mock->expects($this->once())
            ->method('get')
            ->with(
                'dependencies'
            )
            ->willReturn($dependencies);

        $dependencies = (new DependencyStub())->getDependenciesHealthy();

        $mock->expects($this->once())
            ->method('set')
            ->with(
                'dependencies',
                $dependencies
            );

        
        $healthCheck->setDependencyStatus('key', 'Healthy');
    }

    public function testSetHealthCheckBasicInfo()
    {
        $mock = $this->getMemoryMock();
        $healthCheck = new HealthCheck($mock);

        $data = (new HealthCheckStub())->getBasicInfoStub();

        $mock->expects($this->once())
            ->method('set')
            ->with(
                'basic-info',
                $data
            );
        
        $healthCheck->setHealthCheckBasicInfo($data);
    }

    public function testGetHealthCheck()
    {
        $mock = $this->getMemoryMock();
        $healthCheck = new HealthCheck($mock);

        $dependenciesStub = (new DependencyStub())->getDependenciesHealthy();
        $healthCheckStub = (new HealthCheckStub())->getHealthCheckHealthyStub();

        $basicInfo = (new HealthCheckStub())->getBasicInfoStub();
        
        $mock->expects($this->exactly(2))
        ->method('get')
        ->willReturnOnConsecutiveCalls(
            $basicInfo,
            $dependenciesStub
        );

        $response = $healthCheck->getHealthCheck();

        $this->assertEquals($response['name'], $healthCheckStub['name']);
        $this->assertEquals($response['version'], $healthCheckStub['version']);
        $this->assertEquals($response['status'], $healthCheckStub['status']);
        $this->assertEquals($response['dependencies'], $healthCheckStub['dependencies']);
    }

    public function testGetHealthCheckUnhealthy()
    {
        $mock = $this->getMemoryMock();
        $healthCheck = new HealthCheck($mock);

        $dependenciesStub = (new DependencyStub())->getDependenciesUnhealthy();
        $healthCheckStub = (new HealthCheckStub())->getHealthCheckUnhealthyStub();

        $basicInfo = (new HealthCheckStub())->getBasicInfoStub();
        
        $mock->expects($this->exactly(2))
        ->method('get')
        ->willReturnOnConsecutiveCalls(
            $basicInfo,
            $dependenciesStub
        );

        $response = $healthCheck->getHealthCheck();

        $this->assertEquals($response['name'], $healthCheckStub['name']);
        $this->assertEquals($response['version'], $healthCheckStub['version']);
        $this->assertEquals($response['status'], $healthCheckStub['status']);
        $this->assertEquals($response['dependencies'], $healthCheckStub['dependencies']);
    }
    
    public function testGetHealthCheckUnavailable()
    {
        $mock = $this->getMemoryMock();
        $healthCheck = new HealthCheck($mock);

        $dependenciesStub = (new DependencyStub())->getDependenciesUnavailable();
        $healthCheckStub = (new HealthCheckStub())->getHealthCheckUnavailableStub();

        $basicInfo = (new HealthCheckStub())->getBasicInfoStub();
        
        $mock->expects($this->exactly(2))
        ->method('get')
        ->willReturnOnConsecutiveCalls(
            $basicInfo,
            $dependenciesStub
        );

        $response = $healthCheck->getHealthCheck();

        $this->assertEquals($response['name'], $healthCheckStub['name']);
        $this->assertEquals($response['version'], $healthCheckStub['version']);
        $this->assertEquals($response['status'], $healthCheckStub['status']);
        $this->assertEquals($response['dependencies'], $healthCheckStub['dependencies']);
    }

    public function testGetHealthCheckWithOptionalDependencyUnavailable()
    {
        $mock = $this->getMemoryMock();
        $healthCheck = new HealthCheck($mock);

        $dependenciesStub = (new DependencyStub())->getDependenciesOptionalUnavailable();
        $healthCheckStub = (new HealthCheckStub())->getHealthCheckHealthyStub($dependenciesStub);

        $basicInfo = (new HealthCheckStub())->getBasicInfoStub();
        
        $mock->expects($this->exactly(2))
        ->method('get')
        ->willReturnOnConsecutiveCalls(
            $basicInfo,
            $dependenciesStub
        );

        $response = $healthCheck->getHealthCheck();

        $this->assertEquals($response['name'], $healthCheckStub['name']);
        $this->assertEquals($response['version'], $healthCheckStub['version']);
        $this->assertEquals($response['status'], $healthCheckStub['status']);
        $this->assertEquals($response['dependencies'], $healthCheckStub['dependencies']);
    }
}