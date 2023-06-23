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
        $methodsList = [
            'setDependencyStatus',
            'setDependencies',
            'getHealthCheck',
            'setHealthCheckBasicInfo'
        ];

        $builder = $this->getMockBuilder(HealthCheckRepository::class);

        if (version_compare(PHP_VERSION, "7.2.0") >= 0) {
            $builder->onlyMethods($methodsList);
        }else {
            $builder->setMethods($methodsList);
        }
        
        return $builder->getMock();
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