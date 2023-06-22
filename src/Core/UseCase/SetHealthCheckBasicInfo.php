<?php

namespace MadeiraMadeira\HealthCheck\Core\UseCase;

use MadeiraMadeira\HealthCheck\Core\Exceptions\BasicInfoException;

class SetHealthCheckBasicInfo extends UseCase
{
    private $repository;
    
    public function __construct($repository)
    {
        $this->repository = $repository;
    }

    public function execute($data = array())
    {
        $this->validate($data);

        return $this->repository->setHealthCheckBasicInfo($data);
    }

    private function validate($data)
    {
        $errors = [];
        if (empty($data['name'])) {
            $errors[] = "Field \"name\" cannot be empty";
        }
        
        if (empty($data['version'])) {
            $errors[] = "Field \"version\" cannot be empty";
        }

        if (count($errors) > 0) {
            throw new BasicInfoException(
                sprintf("Informations about HealthCheck is not valid - Errors: %s", implode(" - ", $errors)) 
            );
        }
    }
}