<?php

namespace MadeiraMadeira\HealthCheck;
use MadeiraMadeira\HealthCheck\Core\Repositories\HealthCheckRepository;
use MadeiraMadeira\HealthCheck\Core\UseCase\SetDependenciesUseCase;
use MadeiraMadeira\HealthCheck\Core\UseCase\SetDependencyStatusUseCase;
use MadeiraMadeira\HealthCheck\Core\UseCase\SetHealthCheckBasicInfo;
use MadeiraMadeira\HealthCheck\Core\UseCase\GetHealthCheckUseCase;
use MadeiraMadeira\HealthCheck\Infra\Datasources\Memory\HashMapMemory;
use MadeiraMadeira\HealthCheck\Infra\Repositories\HealthCheck;

class Container
{
    private static $dependencies;

    private static function make(string $key, $makeInstance)
    {
        $dependency = Container::get($key);

        if ($dependency) {
            return $dependency;
        }

        $instance = $makeInstance();

        Container::set($key, $instance);

        return $instance;
    }

    private static function get(string $key)
    {
        return !empty(Container::$dependencies[$key]) ? Container::$dependencies[$key] : null;
    }

    private static function set(string $key, $instance)
    {
        Container::$dependencies[$key] = $instance;
    }
    
    public static function getMemory()
    {
        return Container::make(Memory::class, function() {
            return new HashMapMemory();
        });
    }

    public static function getHealthCheckRepository()
    {
        return Container::make(HealthCheckRepository::class, function() {
            return new HealthCheck(Container::getMemory());
        });
    }
    public static function getSetDependencyStatusUseCase()
    {
        return Container::make(SetDependencyStatusUseCase::class, function() {
            return new SetDependencyStatusUseCase(
                Container::getHealthCheckRepository()
            );
        });
    }

    public static function getSetDependenciesUseCase()
    {
        return Container::make(SetDependenciesUseCase::class, function() {
            return new SetDependenciesUseCase(
                Container::getHealthCheckRepository()
            );
        });
    }

    public static function getHealthCheckUseCase()
    {
        return Container::make(GetHealthCheckUseCase::class, function() {
            return new GetHealthCheckUseCase(
                Container::getHealthCheckRepository()
            );
        });
    }

    public static function getSetHealthCheckBasicInfo()
    {
        return Container::make(SetHealthCheckBasicInfo::class, function() {
            return new SetHealthCheckBasicInfo(
                Container::getHealthCheckRepository()
            );
        });
    }
}