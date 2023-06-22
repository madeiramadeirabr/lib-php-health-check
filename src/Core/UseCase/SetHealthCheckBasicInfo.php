<?php

namespace MadeiraMadeira\HealthCheck\Core\UseCase;

class SetHealthCheckBasicInfo extends UseCase
{
    private $repository;
    
    public function __construct($repository)
    {
        $this->repository = $repository;
    }

    public function execute($data = array())
    {
        //validate basic info
        return $this->repository->setHealthCheckBasicInfo($data);
    }
}