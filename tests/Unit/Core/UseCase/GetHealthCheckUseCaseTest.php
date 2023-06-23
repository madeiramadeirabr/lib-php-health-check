<?php

namespace Tests\Unit\Core\UseCase;

use MadeiraMadeira\HealthCheck\Core\Repositories\HealthCheckRepository;
use MadeiraMadeira\HealthCheck\Core\UseCase\GetHealthCheckUseCase;
use PHPUnit\Framework\TestCase;

class GetHealthCheckUseCaseTest extends TestCase
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
        $useCase = new GetHealthCheckUseCase($mock);

        $mock->expects($this->once())
            ->method('getHealthCheck');

        $useCase->execute();
    }
}