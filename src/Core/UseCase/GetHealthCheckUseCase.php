<?php

namespace MadeiraMadeira\HealthCheck\Core\UseCase;

class GetHealthCheckUseCase extends UseCase
{
    private $repository;
    
    public function __construct($repository)
    {
        $this->repository = $repository;
    }

    public function execute($data = array())
    {
        return $this->repository->getHealthCheck();
    }
}