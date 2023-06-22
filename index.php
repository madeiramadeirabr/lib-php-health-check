<?php

require_once 'vendor/autoload.php';

use MadeiraMadeira\HealthCheck\Core\Entities\Dependency;
use MadeiraMadeira\HealthCheck\Core\Entities\Kind;
use MadeiraMadeira\HealthCheck\Core\Entities\Status;
use MadeiraMadeira\HealthCheck\Presentation\HealthCheck;


$a = HealthCheck::getInstance();

$a->setHealthCheckBasicInfo([
    'name' => 'teste',
    'version' => '123'
]);

$a->setDependencies([
    new Dependency([
        'name' => 'madeirafc',
        'kind' => Kind::getMysqlKind(),
        'optional' => true,
        'internal' => true,
    ]),

    new Dependency([
        'name' => 'madeirafc2',
        'kind' => Kind::getMysqlKind(),
        'optional' => false,
        'internal' => true,
    ]),
    
    new Dependency([
        'name' => 'madeirafc3',
        'kind' => Kind::getMysqlKind(),
        'optional' => false,
        'internal' => true,
    ])
]);

$a->setDependencyStatus(
    'madeirafc',
    Status::getUnavailiableStatus()  
);

$a->setDependencyStatus(
    'madeirafc2',
    Status::getUnhealthyStatus()  
);

echo json_encode($a->getHealthCheck());