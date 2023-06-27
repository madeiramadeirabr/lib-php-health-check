<?php

namespace MadeiraMadeira\HealthCheck\Presentation;

use MadeiraMadeira\HealthCheck\Container;

class HealthCheck {

    private static $instance;

    private function __construct()
    {

    }

    private function __clone()
    {

    }

    public static function getInstance()
    {
        if (!HealthCheck::$instance) {
            HealthCheck::$instance = new HealthCheck();
        }

        return HealthCheck::$instance;
    }

    public function setDependencies(array $dependencies) 
    {
        $useCase = Container::getSetDependenciesUseCase();
        $useCase->execute($dependencies);
    }

    public function setDependencyStatus(string $dependencyName, string $status)
    {
        $useCase = Container::getSetDependencyStatusUseCase();
        $useCase->execute([
            'dependencyName' => $dependencyName,
            'status' => $status
        ]);
    }

    public function getHealthCheck()
    {
        $useCase = Container::getHealthCheckUseCase();
        return $useCase->execute();
    }

    public function setHealthCheckBasicInfo(array $basicInfo)
    {
        $useCase = Container::getSetHealthCheckBasicInfo();
        $useCase->execute($basicInfo);
    }

}

