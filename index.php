<?php

require_once 'vendor/autoload.php';

use MadeiraMadeira\HealthCheck\Core\Entities\Kind;
use MadeiraMadeira\HealthCheck\Presentation\HealthCheck;


$a = HealthCheck::getInstance();

$a->setHealthCheckBasicInfo([
    'name' => 'teste',
    'version' => '123'
]);

$a->setDependencies([
    [
        'name' => 'madeirafc',
        'kind' => Kind::getMysqlKind(),
        'optional' => false,
        'internal' => true
    ]
]);
echo json_encode($a->getHealthCheck());