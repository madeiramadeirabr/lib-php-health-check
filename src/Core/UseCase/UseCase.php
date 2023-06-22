<?php

namespace MadeiraMadeira\HealthCheck\Core\UseCase;

use MadeiraMadeira\HealthCheck\Core\Exceptions\UseCaseNotImplementedException;

class UseCase
{
    public function execute($data = array())
    {
        throw new UseCaseNotImplementedException("Use case not implemented");
    }
}