<?php

require_once 'vendor/autoload.php';

use MadeiraMadeira\HealthCheck\Core\Entities\Dependency;
use MadeiraMadeira\HealthCheck\Core\Entities\Kind;
use MadeiraMadeira\HealthCheck\Core\Entities\Status;
use MadeiraMadeira\HealthCheck\Presentation\HealthCheck;

use MadeiraMadeira\HealthCheck\Core\Repositories\RunnerRepository;
use GuzzleHttp\Client;

class MyRunner implements RunnerRepository
{
    public function getStatus()
    {
        $client = new Client();
        $response = $client->get(
            'https://google.com'
        );
        
        if ($response->getStatusCode() > 500) {
            return Status::getUnavailiableStatus();
        }

        return Status::getHealthyStatus();
    }
}

$healthCheckInstance = HealthCheck::getInstance();

$healthCheckInstance->setHealthCheckBasicInfo([
    'name' => 'My application',
    'version' => 'v1'
]);

$healthCheckInstance->setDependencies([
    new Dependency([
        'name' => 'A dummy API',
        'kind' => Kind::getWebserviceKind(),
        'optional' => false,
        'internal' => false,
        'runner' => new MyRunner()
    ]),
]);

echo json_encode($healthCheckInstance->getHealthCheck());