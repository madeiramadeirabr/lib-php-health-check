<?php

namespace Tests\Unit\Infra\Repositories;

use MadeiraMadeira\HealthCheck\Core\Entities\Dependency;
use PHPUnit\Framework\TestCase;
use MadeiraMadeira\HealthCheck\Infra\Repositories\HealthCheck;
use MadeiraMadeira\HealthCheck\Core\Entities\Kind;
use MadeiraMadeira\HealthCheck\Infra\Datasources\Memory\Interfaces\MemoryInterface;

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
        
        $dependencies = [
            [
                'name' => 'key',
                'kind' => Kind::getMysqlKind(),
                'optional' => false,
                'internal' => true,
            ]
        ];

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
        

        $dependencies = [
            new Dependency([
                'name' => 'key',
                'kind' => Kind::getMysqlKind(),
                'optional' => false,
                'internal' => true,
                'status' => 'Healthy'
            ])
        ];

        $mock->expects($this->once())
            ->method('get')
            ->with(
                'dependencies'
            )
            ->willReturn($dependencies);

        $dependencies = [
            new Dependency([
                'name' => 'key',
                'kind' => Kind::getMysqlKind(),
                'optional' => false,
                'internal' => true,
                'status' => 'Unhealthy'
            ])
        ];
        $mock->expects($this->once())
            ->method('set')
            ->with(
                'dependencies',
                $dependencies
            );

        
        $healthCheck->setDependencyStatus('key', 'Unhealthy');
    }

    public function setHealthCheckBasicInfo()
    {
        $this->assertTrue(true);
        
    }

    public function testGetHealthCheck()
    {
        $this->assertTrue(true);
    }
}