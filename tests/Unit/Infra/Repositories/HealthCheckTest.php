<?php

namespace Tests\Unit\Infra\Repositories;

use MadeiraMadeira\HealthCheck\Core\Entities\Status;
use PHPUnit\Framework\TestCase;
use MadeiraMadeira\HealthCheck\Infra\Repositories\HealthCheck;
use MadeiraMadeira\HealthCheck\Infra\Datasources\Memory\Interfaces\MemoryInterface;
use Tests\Mock\DependencyStub;
use Tests\Mock\HealthCheckStub;

class HealthCheckTest extends TestCase
{
    public function getMemoryMock()
    {
        $methodsList = [
            'set',
            'get',
            'all',
            'clear'
        ];
        $builder = $this->getMockBuilder(MemoryInterface::class);

        if (version_compare(PHP_VERSION, "7.2.0") >= 0) {
            $builder->onlyMethods($methodsList);
        }else {
            $builder->setMethods($methodsList);
        }
        
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

        
        $healthCheck->setDependencyStatus('key', Status::getHealthyStatus());
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
        $healthCheckStub = (new HealthCheckStub())->getHealthCheckStub();

        $basicInfo = (new HealthCheckStub())->getBasicInfoStub();
        
        $mock->expects($this->exactly(3))
        ->method('get')
        ->willReturnOnConsecutiveCalls(
            $dependenciesStub,
            $dependenciesStub,
            $basicInfo
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
        
        $mock->expects($this->exactly(3))
        ->method('get')
        ->willReturnOnConsecutiveCalls(
            $dependenciesStub,
            $dependenciesStub,
            $basicInfo
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
        
        $mock->expects($this->exactly(3))
        ->method('get')
        ->willReturnOnConsecutiveCalls(
            $dependenciesStub,
            $dependenciesStub,
            $basicInfo
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
        $healthCheckStub = (new HealthCheckStub())->getHealthCheckStub($dependenciesStub);

        $basicInfo = (new HealthCheckStub())->getBasicInfoStub();
        
        $mock->expects($this->exactly(3))
        ->method('get')
        ->willReturnOnConsecutiveCalls(
            $dependenciesStub,
            $dependenciesStub,
            $basicInfo
        );

        $response = $healthCheck->getHealthCheck();

        $this->assertEquals($response['name'], $healthCheckStub['name']);
        $this->assertEquals($response['version'], $healthCheckStub['version']);
        $this->assertEquals($response['status'], $healthCheckStub['status']);
        $this->assertEquals($response['dependencies'], $healthCheckStub['dependencies']);
    }

    public function testGetHealthCheckDependencyWithRunner()
    {
        $mock = $this->getMemoryMock();
        $healthCheck = new HealthCheck($mock);

        $dependenciesStub = (new DependencyStub())->getDependenciesWithRunner();
        $healthCheckStub = (new HealthCheckStub())->getHealthCheckStub($dependenciesStub);

        $basicInfo = (new HealthCheckStub())->getBasicInfoStub();
        
        $mock->expects($this->exactly(3))
        ->method('get')
        ->willReturnOnConsecutiveCalls(
            $dependenciesStub,
            $dependenciesStub,
            $basicInfo
        );

        $response = $healthCheck->getHealthCheck();

        $this->assertEquals($response['name'], $healthCheckStub['name']);
        $this->assertEquals($response['version'], $healthCheckStub['version']);
        $this->assertEquals($response['status'], $healthCheckStub['status']);
        $this->assertEquals($response['dependencies'], $healthCheckStub['dependencies']);
    }

    public function testGetHealthCheckDependencyWithRunnerUnhealthy()
    {
        $mock = $this->getMemoryMock();
        $healthCheck = new HealthCheck($mock);

        $dependenciesStub = (new DependencyStub())->getDependenciesWithRunnerUnhealthy();
        $healthCheckStub = (new HealthCheckStub())->getHealthCheckUnhealthyStub();

        $basicInfo = (new HealthCheckStub())->getBasicInfoStub();
        
        $mock->expects($this->exactly(3))
        ->method('get')
        ->willReturnOnConsecutiveCalls(
            $dependenciesStub,
            $dependenciesStub,
            $basicInfo
        );

        $response = $healthCheck->getHealthCheck();

        $this->assertEquals($response['name'], $healthCheckStub['name']);
        $this->assertEquals($response['version'], $healthCheckStub['version']);
        $this->assertEquals($response['status'], $healthCheckStub['status']);
        $this->assertEquals($response['dependencies'], $healthCheckStub['dependencies']);
    }

    public function testGetHealthCheckDependencyWithRunnerUnavaiable()
    {
        $mock = $this->getMemoryMock();
        $healthCheck = new HealthCheck($mock);

        $dependenciesStub = (new DependencyStub())->getDependenciesWithRunnerUnavaiable();
        $healthCheckStub = (new HealthCheckStub())->getHealthCheckUnavailableStub();

        $basicInfo = (new HealthCheckStub())->getBasicInfoStub();
        
        $mock->expects($this->exactly(3))
        ->method('get')
        ->willReturnOnConsecutiveCalls(
            $dependenciesStub,
            $dependenciesStub,
            $basicInfo
        );

        $response = $healthCheck->getHealthCheck();

        $this->assertEquals($response['name'], $healthCheckStub['name']);
        $this->assertEquals($response['version'], $healthCheckStub['version']);
        $this->assertEquals($response['status'], $healthCheckStub['status']);
        $this->assertEquals($response['dependencies'], $healthCheckStub['dependencies']);
    }
}