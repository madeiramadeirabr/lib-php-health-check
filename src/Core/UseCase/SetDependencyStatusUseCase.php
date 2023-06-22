<?php

namespace MadeiraMadeira\HealthCheck\Core\UseCase;

use MadeiraMadeira\HealthCheck\Core\Entities\Status;
use MadeiraMadeira\HealthCheck\Core\Exceptions\UnexpectedStatusException;

class SetDependencyStatusUseCase extends UseCase
{
    private $repository;
    
    public function __construct($repository)
    {
        $this->repository = $repository;
    }

    public function execute($data = array())
    {
        $this->validate($data);

        $this->repository->setDependencyStatus($data['dependencyName'], $data['status']);
    }

    private function validate($data)
    {
        if (!Status::isValidStatus($data['status'])) {
            throw new UnexpectedStatusException(
                sprintf("Unexpected status %s", 
                    $data['status']
                )
            );
        }
    }
}