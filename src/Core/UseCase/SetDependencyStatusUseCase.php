<?php

namespace MadeiraMadeira\HealthCheck\Core\UseCase;

class SetDependencyStatusUseCase extends UseCase
{
    private $repository;
    
    public function __construct($repository)
    {
        $this->repository = $repository;
    }

    public function execute($data = array())
    {
        //validate status
        $this->repository->setDependencyStatus($data['dependencyName'], $data['status']);
    }
}