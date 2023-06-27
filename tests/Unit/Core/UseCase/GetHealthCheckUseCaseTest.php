<?php

namespace Tests\Unit\Core\UseCase;

use MadeiraMadeira\HealthCheck\Core\Repositories\HealthCheckRepository;
use MadeiraMadeira\HealthCheck\Core\UseCase\GetHealthCheckUseCase;
use PHPUnit\Framework\TestCase;

class GetHealthCheckUseCaseTest extends TestCase
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
        $useCase = new GetHealthCheckUseCase($mock);

        $mock->expects($this->once())
            ->method('getHealthCheck');

        $useCase->execute();
    }
}