<?php

namespace Tests\Unit\Core\UseCase;

use MadeiraMadeira\HealthCheck\Core\Entities\Dependency;
use MadeiraMadeira\HealthCheck\Core\Entities\Kind;
use MadeiraMadeira\HealthCheck\Core\Repositories\HealthCheckRepository;
use MadeiraMadeira\HealthCheck\Core\UseCase\SetDependenciesUseCase;
use PHPUnit\Framework\TestCase;

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

        $data = [
            new Dependency([
                'name' => 'dependency 1',
                'kind' => Kind::getMysqlKind(),
                'optional' => true,
                'internal' => true,
            ]),
        
            new Dependency([
                'name' => 'dependency 2',
                'kind' => Kind::getMysqlKind(),
                'optional' => false,
                'internal' => true,
            ]),
            
            new Dependency([
                'name' => 'dependency 3',
                'kind' => Kind::getMysqlKind(),
                'optional' => false,
                'internal' => true,
            ])
        ];

        $mock->expects($this->once())
            ->method('setDependencies')
            ->with(
                $data
            );
        
        $useCase->execute($data);
    }
}