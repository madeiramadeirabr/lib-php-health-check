<?php

namespace MadeiraMadeira\HealthCheck\Core\Entities;

class Kind
{
    private static $webservice = 'webservice';

    private static $sqs = 'sqs';

    private static $s3 = 's3';

    private static $sns = 'sns';

    private static $mysql = 'mysql';

    private static $postgresql = 'postgresql';

    private static $mongodb = 'mongodb';

    private static $redis = 'redis';

    private static $elasticsearch = 'elasticsearch';

    private static $other = 'other';

    private static $availableKinds = [
        'webservice',
        'sqs',
        's3',
        'sns',
        'mysql',
        'postgresql',
        'mongodb',
        'redis',
        'elasticSearch',
        'other'
    ];

    public static function isValidKind(string $kind)
    {
        return in_array($kind, self::$availableKinds);
    }

    public static function getWebserviceKind()
    {
        return self::$webservice;
    }

    public static function getSQSKind()
    {
        return self::$sqs;
    }

    public static function getS3Kind()
    {
        return self::$s3;
    }
    
    public static function getSNSKind()
    {
        return self::$sns;
    }
    
    public static function getMysqlKind()
    {
        return self::$mysql;
    }
    
    public static function getPostgresqlKind()
    {
        return self::$postgresql;
    }
    
    public static function getMongoDBKind()
    {
        return self::$mongodb;
    }
    
    public static function getRedisKind()
    {
        return self::$redis;
    }
    
    public static function getElasticSearchKind()
    {
        return self::$elasticsearch;
    }
    
    public static function getOtherKind()
    {
        return self::$other;
    }
}