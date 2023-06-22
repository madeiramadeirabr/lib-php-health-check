<?php

namespace MadeiraMadeira\HealthCheck\Core\Entities;

class Status
{
    private static $healthy = 'Healthy';

    private static $unhealthy = 'Unhealthy';

    private static $unavailiable = 'Unavailiable';

    private static $availableStatuses = [
        'Healthy',
        'Unhealthy',
        'Unavailiable'
    ];

    public static function isValidStatus(string $status)
    {
        return in_array($status, self::$availableStatuses);
    }

    public static function getHealthyStatus()
    {
        return self::$healthy;
    }

    public static function getUnhealthyStatus()
    {
        return self::$unhealthy;
    }

    public static function getUnavailiableStatus()
    {
        return self::$unavailiable;
    }
}