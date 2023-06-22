<?php

namespace MadeiraMadeira\HealthCheck\Core\Entities;

class Kind
{
    private static $webservice = 'WebService';
    private static $sqs = 'SQS';
    private static $s3 = 'S3';
    private static $sns = 'SNS';
    private static $mysql = 'Mysql';
    private static $postgresql = 'Postgresql';
    private static $mongodb = 'Mongodb';
    private static $redis = 'Redis';
    private static $elasticsearch = 'Elasticsearch';
    private static $other = 'Other';

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