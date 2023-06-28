# Usando a biblioteca

Nessa documentação temos:
- [Usando a biblioteca](#usando-a-biblioteca)
  - [Descrição](#descrição)
  - [Registrando as informações básicas](#registrando-as-informações-básicas)
  - [Registrando as dependências](#registrando-as-dependências)
  - [Alterar status das dependências](#alterar-status-das-dependências)
  - [Exemplo de uso](#exemplo-de-uso)

## Descrição

Essa é a biblioteca que implementa a RFC de Health Check. A descrição completa você pode encontrar nesse [link](https://github.com/madeiramadeirabr/mmrfc/blob/main/rfcs/MMRFC%207%20-%20Health%20Check%20Standard.md). 

## Registrando as informações básicas

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

## Registrando as dependências
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

## Alterar status das dependências

A biblioteca disponibiliza duas formas de atualizar o status atual das dependências:

- Forma manual
- Por "runner"

**Alterar status de forma manual:**

Para alterar o status da dependência de forma manual basta chamar a função de alterar status da dependência pela instância de health check. 

Exemplo:
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

Implementar um runner para alterar de forma automática é a forma fácil de gerenciar os status das aplicações. Para utilizar esse modo precisamos:

- Escrever uma classe que implementa a interface `RunnerRepository`
- Retornar algum status disponível na classe `Status`
- Ao setar as dependências no Health Check, deve ser repassado uma instância do `Runner` implementado.

Lembrando que, o `Runner`, caso fornecido, sempre será executado ao chamar a função `getHealthCheck` da instância de Health Check.

Exemplo:
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