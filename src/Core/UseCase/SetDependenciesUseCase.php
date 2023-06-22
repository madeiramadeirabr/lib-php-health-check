<?php

namespace MadeiraMadeira\HealthCheck\Core\UseCase;

class SetDependenciesUseCase extends UseCase
{
    private $repository;
    
    public function __construct($repository)
    {
        $this->repository = $repository;
    }

    public function execute($data = array())
    {
        $this->repository->setDependencies($data);
    }
}