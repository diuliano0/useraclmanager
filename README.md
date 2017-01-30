# betagt/useraclmanager
`Controle de usuário com Laravel LTS 5.3`

[![Latest Stable Version](https://poser.pugx.org/betagt/useraclmanager/v/stable)](https://packagist.org/packages/betagt/useraclmanager) [![Total Downloads](https://poser.pugx.org/betagt/useraclmanager/downloads)](https://packagist.org/packages/betagt/useraclmanager) [![Latest Unstable Version](https://poser.pugx.org/betagt/useraclmanager/v/unstable)](https://packagist.org/packages/betagt/useraclmanager) [![License](https://poser.pugx.org/betagt/useraclmanager/license)](https://packagist.org/packages/betagt/useraclmanager)
[![Analytics](https://ga-beacon.appspot.com/UA-61050740-1/l5-repository/readme)](https://packagist.org/packages/betagt/useraclmanager)
[![Code Climate](https://codeclimate.com/github/betagt/useraclmanager/badges/gpa.svg)](https://codeclimate.com/github/betagt/useraclmanager)
## Instalação

Execute o seguinte comando para obter a versão mais recente do pacote
```terminal
composer require betagt/useraclmanager
```
No seu `config/app.php` add `\BetaGT\UserAclManager\UserAclManagerServiceProvider::class` no final do array `providers`:
```php
   'providers' => [
       ...
       Prettus\Repository\Providers\RepositoryServiceProvider::class,
   ],
```
#### Autenticação Passport 
No seu `config/auth.php` no array de guards alterar para o drive do `passport`:
```php
   'api' => [
       'driver' => 'passport',
       'provider' => 'users',
   ],
```
No seu `config/auth.php` no array de providers alterar para o `model` de usuráio:
```php
   'providers' => [
       'users' => [
           'driver' => 'eloquent',
           'model' => \BetaGT\UserAclManager\Models\User::class,
       ],
```
Publicando configuração
```shell
php artisan vendor:publish --force
```
#### Rodando as Seeders
No arquivo `DatabaseSeeder.php` adicione as linhas no método `run()`
```
 public function run()
     {
          $this->call(UsersTableSeeder::class);
          $this->call(PermissionTableSeeder::class);
     }
```
#### Banco de dados
Acesse o arquivo `.env` na raiz e adicione as configurações de banco de dados antes dos próximos passos.

#### Rodando migrates 
Instalando banco
```shell
php artisan migrate
```

#### Instalação Laravel-Passport 
Instalando `Laravel-Passport` no projeto
```shell
php artisan passport:install
```

#### Instalação Laravel-Auditing 
Instalando `Laravel-Auditing` no projeto
```shell
php artisan auditing:install
```

#### Autenticação Passport 
No seu `config/auditing.php` no array de configuração altere a linha que indica a rota da classe de usuário:
```php
   'model' => \BetaGT\UserAclManager\Models\User::class,
```

### Iniciando banco de dados
```shell
php artisan migrate --seed
```
ou
```
php artisan migrate
php artisan db:seed
```
#### Registrando Rotas
No seu arquivo `Providers/AuthServiceProvider.php` insira no método `boot()`:
```php
   \BetaGT\UserAclManager\UserAclManager::routes();
```
#### Registrando Rotas passport
No seu arquivo `Providers/AuthServiceProvider.php` insira no método `boot()`:
```php
   Passport::routes();
   Passport::tokensExpireIn(Carbon::now()->addHour(5));
   Passport::refreshTokensExpireIn(Carbon::now()->addDay(1));
```

### Outras dependências do projeto
- [Laravel 5 Passport](https://laravel.com/docs/master/passport)
- [Laravel 5 Laravel-Auditing](https://github.com/owen-it/laravel-auditing-doc/blob/master/README.md)
- [Laravel 5 Repositories](https://github.com/andersao/l5-repository)
- [PHP league Fractal](http://fractal.thephpleague.com/installation/)
- [kodeine - Laravel ACL](https://github.com/kodeine/laravel-acl/wiki/Installation)
- [Laravel 5 Repositories](https://github.com/andersao/l5-repository)