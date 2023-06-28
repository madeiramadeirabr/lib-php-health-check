# Usando a biblioteca

Nessa documentação temos:
- [Usando a biblioteca](#usando-a-biblioteca)
  - [Descrição](#descrição)
  - [Registrando as informações básicas](#registrando-as-informações-básicas)
  - [Registrando as dependências](#registrando-as-dependências)
  - [Alterar status das dependências](#alterar-status-das-dependências)
  - [Exemplo de uso](#exemplo-de-uso)
  - [Implementação em Laravel](#implementação-em-laravel)
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

## Implementação em Laravel

1. Crie um novo provider `HealthCheckServiceProvider.php` em `app/Providers`
```php
<?php

namespace App\Providers;

use App\Services\MyRunner;
use Illuminate\Support\ServiceProvider;
use MadeiraMadeira\HealthCheck\Core\Entities\Dependency;
use MadeiraMadeira\HealthCheck\Core\Entities\Kind;
use MadeiraMadeira\HealthCheck\Presentation\HealthCheck;

class HealthCheckServiceProvider extends ServiceProvider
{

    public function register(): void
    {

    }

    public function boot(): void
    {
        $this->app->singleton(HealthCheck::class, function() {
            $instance = HealthCheck::getInstance();
            $instance->setHealthCheckBasicInfo([
                'name' => getenv('APP_NAME') . '-' . getenv('ENVIRONMENT_NAME'),
                'version' => 'v1'
            ]);

            $instance->setDependencies([
                new Dependency([
                    'name' => 'My database',
                    'kind' => Kind::getMysqlKind(),
                    'optional' => false,
                    'internal' => true,
                ]),
                new Dependency([
                    'name' => 'any dependency 1',
                    'kind' => Kind::getWebserviceKind(),
                    'optional' => false,
                    'internal' => false,
                ]),
                new Dependency([
                    'name' => 'any dependency 2',
                    'kind' => Kind::getWebserviceKind(),
                    'optional' => true,
                    'internal' => false,
                    'runner' => new MyRunner()
                ]),
            ]);

            return $instance;
        });
    }
}
```
2. Registre suas dependências
   1.  Nessa etapa, crie os seus `Runners` para validar qualquer tipo de dependência, nesse exemplo criei um arquivo chamado `MyRunner` em app/Services
```php
<?php

namespace App\Services;

use MadeiraMadeira\HealthCheck\Core\Entities\Status;
use MadeiraMadeira\HealthCheck\Core\Repositories\RunnerRepository;

class MyRunner implements RunnerRepository
{
    public function getStatus()
    {
        $value = rand(0, 100);

        if ($value < 90) {
            return Status::getHealthyStatus();
        }

        if ($value <= 95) {
            return Status::getUnhealthyStatus();
        }

        return Status::getUnavailiableStatus();
    }
}
```
3. Registre o provider no serviço de `Autoloaded Service Providers` em `config/app.php`
   1. Nesse caso, verifique a versão do Laravel em que você esteja rodando para registrar corretamente o Provider.
```php
...
    'providers' => ServiceProvider::defaultProviders()->merge([
        /*
         * Package Service Providers...
         */

        /*
         * Application Service Providers...
         */
        App\Providers\AppServiceProvider::class,
        App\Providers\AuthServiceProvider::class,
        // App\Providers\BroadcastServiceProvider::class,
        App\Providers\EventServiceProvider::class,
        App\Providers\RouteServiceProvider::class,
        App\Providers\HealthCheckServiceProvider::class,
    ])->toArray(),
...
```

4. Crie um Controller `HealthCheckController.php` em `app/Http/Controllers`
```php
<?php

namespace App\Http\Controllers;

use MadeiraMadeira\HealthCheck\Presentation\HealthCheck;

class HealthCheckController extends Controller
{
    public function __construct(private HealthCheck $instance)
    {
        $this->instance = $instance;
    }

    public function healthCheck()
    {
        return response()->json($this->instance->getHealthCheck());
    }
}
```
5. Registre as rotas `/health-check/alive` e `/health-check/status` em `routes/api.php`
```php
...
Route::get("/health-check/alive", function (Request $request) {
    return response()->json([
        'message' => "I'm alive!"
    ]);
});
Route::get("/health-check/status", [HealthCheckController::class, 'healthCheck']);
...
```
6. A partir disso, será possível você acessar o Health Check da sua aplicação.
```json
{
  "name": "my-application-development",
  "version": "v1",
  "system": {
    "cpu": {
      "utilization": 0.275
    },
    "memory": {
      "total": 12120.288,
      "used": 7339.304
    }
  },
  "status": "healthy",
  "timestamp": "2023-06-28 17:14:12.000000",
  "dependencies": [
    {
      "name": "My database",
      "kind": "mysql",
      "status": "healthy",
      "internal": true,
      "optional": false
    },
    {
      "name": "any dependency 1",
      "kind": "webservice",
      "status": "healthy",
      "internal": false,
      "optional": false
    },
    {
      "name": "any dependency 2",
      "kind": "webservice",
      "status": "healthy",
      "internal": false,
      "optional": true
    }
  ]
}
```