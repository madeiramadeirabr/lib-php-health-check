# Usando a biblioteca

Nessa documentação temos:
- [Usando a biblioteca](#usando-a-biblioteca)
  - [Descrição](#descrição)
  - [Registrar de informações básicas](#registrar-de-informações-básicas)
  - [Registrar de dependências](#registrar-de-dependências)
  - [Exemplo de uso](#exemplo-de-uso)

## Descrição

o que é
descrição da rfc
informações de sistema
informações das dependências
    - kind
    - status
    - internal
    - opcional
    - runner
## Registrar de informações básicas

```php
<?php

use MadeiraMadeira\HealthCheck\Presentation\HealthCheck;

...
$healthCheckInstance = HealthCheck::getInstance();
$healthCheckInstance->setHealthCheckBasicInfo([
    'name' => 'My Application',
    'version' => 'v1'
]);
```

## Registrar de dependências
```php

use MadeiraMadeira\HealthCheck\Core\Entities\Dependencies;
...

$healthCheckInstance->setDependencies([
    new Dependency([
        'name' => 'My database',
        'kind' => Kind::getMysqlKind(),
        'optional' => false,
        'internal' => true,
    ]),

    new Dependency([
        'name' => 'A dummy API',
        'kind' => Kind::getWebserviceKind(),
        'optional' => false,
        'internal' => false
    ])
])
```

**Alterar status de forma manual:**

```php

<?php

use MadeiraMadeira\HealthCheck\Core\Entities\Status;

...
$healthcheckInstance->setDependencyStatus(
    'My database', 
    Status::getUnhealthyStatus()
);
```

**Alterar status por runner:**

```php
<?php

use MadeiraMadeira\HealthCheck\Core\Entities\Dependency;
use MadeiraMadeira\HealthCheck\Core\Entities\Kind;
use MadeiraMadeira\HealthCheck\Core\Entities\Status;
use MadeiraMadeira\HealthCheck\Core\Repositories\RunnerRepository;
use MadeiraMadeira\HealthCheck\Presentation\HealthCheck;
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

```


## Exemplo de uso
```php
<?php

require_once 'vendor/autoload.php';

use MadeiraMadeira\HealthCheck\Core\Entities\Dependency;
use MadeiraMadeira\HealthCheck\Core\Entities\Kind;
use MadeiraMadeira\HealthCheck\Core\Entities\Status;
use MadeiraMadeira\HealthCheck\Presentation\HealthCheck;


$healthCheckInstance = HealthCheck::getInstance();

$healthCheckInstance->setHealthCheckBasicInfo([
    'name' => 'My application',
    'version' => 'v1'
]);

$healthCheckInstance->setDependencies([
    new Dependency([
        'name' => 'My database',
        'kind' => Kind::getMysqlKind(),
        'optional' => false,
        'internal' => true,
    ]),

    new Dependency([
        'name' => 'A dummy API',
        'kind' => Kind::getWebserviceKind(),
        'optional' => false,
        'internal' => false
    ]),

]);

$healthCheckInstance->setDependencyStatus(
    'My database',
    Status::getUnhealthyStatus()  
);

$healthCheckInstance->setDependencyStatus(
    'A dummy API',
    Status::getHealthyStatus()  
);

echo json_encode($healthCheckInstance->getHealthCheck());
```

**Exemplo de resposta:**

```json
{
  "name": "My application",
  "version": "v1",
  "system": {
    "cpu": {
      "utilization": 0.24
    },
    "memory": {
      "total": 12120.288,
      "used": 6578.08
    }
  },
  "status": "unhealthy",
  "timestamp": "2023-06-27 14:18:53.000000",
  "dependencies": [
    {
      "name": "My database",
      "kind": "mysql",
      "status": "unhealthy",
      "internal": true,
      "optional": false
    },
    {
      "name": "A dummy API",
      "kind": "webservice",
      "status": "healthy",
      "internal": false,
      "optional": false
    }
  ]
}
```