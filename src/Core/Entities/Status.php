<?php

namespace MadeiraMadeira\HealthCheck\Core\Entities;

class Status
{
    private static $healthy = 'healthy';

    private static $unhealthy = 'unhealthy';

    private static $unavailiable = 'unavailiable';

    private static $availableStatuses = [
        'healthy',
        'unhealthy',
        'unavailiable'
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