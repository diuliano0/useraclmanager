<?php
/**
 * Created by PhpStorm.
 * User: dsoft
 * Date: 30/12/2016
 * Time: 10:44
 */

namespace BetaGT\UserAclManager\Providers;


use BetaGT\UserAclManager\Repositories\ClientRepository;
use BetaGT\UserAclManager\Repositories\ClientRepositoryEloquent;
use BetaGT\UserAclManager\Repositories\PermissionRepository;
use BetaGT\UserAclManager\Repositories\PermissionRepositoryEloquent;
use BetaGT\UserAclManager\Repositories\RoleRepository;
use BetaGT\UserAclManager\Repositories\RoleRepositoryEloquent;
use BetaGT\UserAclManager\Repositories\UserRepository;
use BetaGT\UserAclManager\Repositories\UserRepositoryEloquent;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            UserRepository::class,
            UserRepositoryEloquent::class
        );
        $this->app->bind(
            RoleRepository::class,
            RoleRepositoryEloquent::class
        );
        $this->app->bind(
            PermissionRepository::class,
            PermissionRepositoryEloquent::class
        );
        $this->app->bind(
            ClientRepository::class,
            ClientRepositoryEloquent::class
        );

    }
}