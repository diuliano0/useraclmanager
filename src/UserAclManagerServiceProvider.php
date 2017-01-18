<?php
namespace BetaGT\UserAclManager;
use Illuminate\Support\ServiceProvider;
use BetaGT\UserAclManager\Repositories\PermissionRepository;
use BetaGT\UserAclManager\Repositories\PermissionRepositoryEloquent;
use BetaGT\UserAclManager\Repositories\RoleRepository;
use BetaGT\UserAclManager\Repositories\RoleRepositoryEloquent;
use BetaGT\UserAclManager\Repositories\UserRepository;
use BetaGT\UserAclManager\Repositories\UserRepositoryEloquent;
use OwenIt\Auditing\AuditingServiceProvider;

/**
 * Created by PhpStorm.
 * User: dsoft
 * Date: 13/01/2017
 * Time: 16:05
 */
class UserAclManagerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishMigration();
        $this->publishConfig();
    }
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/config/acl.php', 'acl'
        );
        $this->app->register(\Prettus\Repository\Providers\RepositoryServiceProvider::class);
        $this->app->register(AuditingServiceProvider::class);
        //verificar o publish que está indo errado
        //$this->app->register(\Kodeine\Acl\AclServiceProvider::class);
        /*$loader = \AliasLoader::getInstance();
        $loader->alias('Breadcrumbs', \DaveJamesMiller\Breadcrumbs\Facade::class);*/
        $this->app->bind(UserRepository::class, UserRepositoryEloquent::class);
        $this->app->bind(RoleRepository::class, RoleRepositoryEloquent::class);
        $this->app->bind(PermissionRepository::class, PermissionRepositoryEloquent::class);
    }

    public function publishMigration()
    {
        $this->publishes([
            __DIR__ . '/migrations/' => base_path('/database/migrations'),
        ], 'migrations');
        $this->publishes([
            __DIR__ . '/Criteria' => base_path('/Criteria'),
        ], 'criteria');
        $this->publishes([
            __DIR__ . '/Models' => base_path('/Models'),
        ], 'models');
        $this->publishes([
            __DIR__ . '/Presenters' => base_path('/Presenter'),
        ], 'presenter');
        $this->publishes([
            __DIR__ . '/Repositories' => base_path('/Repositories'),
        ], 'repositories');
        $this->publishes([
            __DIR__ . '/Services' => base_path('/Services'),
        ], 'services');
        $this->publishes([
            __DIR__ . '/Transformers' => base_path('/Transformers'),
        ], 'transformers');
        $this->publishes([
            __DIR__ . '/Http' => base_path('/Http'),
        ], 'http');
    }
    /**
     * Publish the config file to the application config directory
     */
    public function publishConfig()
    {
        $this->publishes([
            __DIR__ . '/config/acl.php' => config_path('acl.php'),
        ], 'config');
    }
}