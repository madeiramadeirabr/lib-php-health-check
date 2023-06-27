<?php

namespace Tests\Unit\Core\UseCase;

use MadeiraMadeira\HealthCheck\Core\Exceptions\UseCaseNotImplementedException;
use MadeiraMadeira\HealthCheck\Core\UseCase\UseCase;
use PHPUnit\Framework\TestCase;

class UseCaseTest extends TestCase
{
    public function testExecute()
    {
        $useCase = new UseCase();

        $this->expectException(UseCaseNotImplementedException::class);
        $useCase->execute();
    }
}