<?php

namespace Tests\Unit\Core\UseCase;

use MadeiraMadeira\HealthCheck\Core\Exceptions\BasicInfoException;
use MadeiraMadeira\HealthCheck\Core\Repositories\HealthCheckRepository;
use MadeiraMadeira\HealthCheck\Core\UseCase\SetHealthCheckBasicInfo;
use PHPUnit\Framework\TestCase;

class SetHealthCeckBasicInfoTest extends TestCase
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

        $data = [
            'name' => 'test',
            'version' => '123'
        ];

        $mock->expects($this->once())
            ->method('setHealthCheckBasicInfo')
            ->with(
                $data
            );

        $useCase->execute($data);
    }

    public function testExecuteThrowingException()
    {
        $mock = $this->getHealthCheckRepositoryMock();
        $useCase = new SetHealthCheckBasicInfo($mock);

        $data = [
            'name' => '',
            'version' => ''
        ];

        $this->expectException(BasicInfoException::class);

        $useCase->execute($data);
    }
}