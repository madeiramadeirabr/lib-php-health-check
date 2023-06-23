<?php

namespace Tests\Unit\Core\UseCase;

use MadeiraMadeira\HealthCheck\Core\Exceptions\UnexpectedStatusException;
use MadeiraMadeira\HealthCheck\Core\Repositories\HealthCheckRepository;
use MadeiraMadeira\HealthCheck\Core\UseCase\SetDependencyStatusUseCase;
use PHPUnit\Framework\TestCase;
use Tests\Mock\DependencyStub;

class SetDependencyStatusUseCaseTest extends TestCase
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
        $useCase = new SetDependencyStatusUseCase($mock);

        $data = (new DependencyStub())->getValidDependencyData();

        $mock->expects($this->once())
            ->method('setDependencyStatus')
            ->with(
                $data['dependencyName'],
                $data['status']
            );
        
        $useCase->execute($data);
    }

    public function testExecuteThrowingException()
    {
        $mock = $this->getHealthCheckRepositoryMock();
        $useCase = new SetDependencyStatusUseCase($mock);

        $data = (new DependencyStub())->getInvalidDependencyData();

        $this->expectException(UnexpectedStatusException::class);

        $useCase->execute($data);
    }
}