<?php

namespace Tests\Unit\Core\UseCase;

use MadeiraMadeira\HealthCheck\Core\Repositories\HealthCheckRepository;
use MadeiraMadeira\HealthCheck\Core\UseCase\SetDependenciesUseCase;
use PHPUnit\Framework\TestCase;
use Tests\Mock\DependencyStub;

class SetDependenciesUseCaseTest extends TestCase
{
    private function getHealthCheckRepositoryMock()
    {
        $mock = $this->getMockBuilder(HealthCheckRepository::class)
                    ->onlyMethods([
                        'setDependencyStatus',
                        'setDependencies',
                        'getHealthCheck',
                        'setHealthCheckBasicInfo'
                    ])
                    ->getMock();
        
        return $mock;
    }

    public function testExecute()
    {
        $mock = $this->getHealthCheckRepositoryMock();
        $useCase = new SetDependenciesUseCase($mock);

        $dependenciesStub = (new DependencyStub())->getDependenciesHealthy();
        
        $mock->expects($this->once())
            ->method('setDependencies')
            ->with(
                $dependenciesStub
            );
        
        $useCase->execute($dependenciesStub);
    }
}