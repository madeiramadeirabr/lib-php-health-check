<?php

namespace Tests\Unit\Core\UseCase;

use MadeiraMadeira\HealthCheck\Core\Exceptions\BasicInfoException;
use MadeiraMadeira\HealthCheck\Core\Repositories\HealthCheckRepository;
use MadeiraMadeira\HealthCheck\Core\UseCase\SetHealthCheckBasicInfo;
use PHPUnit\Framework\TestCase;
use Tests\Mock\HealthCheckStub;

class SetHealthCheckBasicInfoTest extends TestCase
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
        $useCase = new SetHealthCheckBasicInfo($mock);

        $basicInfoStub = (new HealthCheckStub())->getBasicInfoStub();

        $mock->expects($this->once())
            ->method('setHealthCheckBasicInfo')
            ->with(
                $basicInfoStub
            );

        $useCase->execute($basicInfoStub);
    }

    public function testExecuteThrowingException()
    {
        $mock = $this->getHealthCheckRepositoryMock();
        $useCase = new SetHealthCheckBasicInfo($mock);

        $basicInfoStub = (new HealthCheckStub())->getInvalidBasicInfo();

        $this->expectException(BasicInfoException::class);

        $useCase->execute($basicInfoStub);
    }
}