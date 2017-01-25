<?php
namespace BetaGT\UserAclManager;
use BetaGT\UserAclManager\Providers\RepositoryServiceProvider;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;
use Laravel\Passport\PassportServiceProvider;
use OwenIt\Auditing\AuditingServiceProvider;
use Illuminate\Foundation\AliasLoader;

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
        $this->app->register(RepositoryServiceProvider::class);
        $this->app->register(PassportServiceProvider::class);
        $loader = AliasLoader::getInstance();
        $loader->alias('acl', \Kodeine\Acl\Middleware\HasPermission::class);
        Passport::routes();
        //verificar o publish que está indo errado
        //$this->app->register(\Kodeine\Acl\AclServiceProvider::class);
        /*$this->app->bind(UserRepository::class, UserRepositoryEloquent::class);
        $this->app->bind(RoleRepository::class, RoleRepositoryEloquent::class);
        $this->app->bind(PermissionRepository::class, PermissionRepositoryEloquent::class);*/
        //php artisan auditing:install
    }

    public function publishMigration()
    {
        $this->publishes([
            __DIR__ . '/database/migrations/' => base_path('/database/migrations'),
        ], 'migrations');
        $this->publishes([
            __DIR__ . '/database/seeds/' => base_path('/database/seeds'),
        ], 'seeds');
        $this->publishes([
            __DIR__ . '/database/factories/' => base_path('/database/factories'),
        ], 'factories');

        /*$this->publishes([
            __DIR__ . '/Criteria' => base_path('/app/Criteria'),
        ], 'criteria');

        $this->publishes([
            __DIR__ . '/Models' => base_path('/app/Models'),
        ], 'models');

        $this->publishes([
            __DIR__ . '/Presenters' => base_path('/app/Presenter'),
        ], 'presenter');

        $this->publishes([
            __DIR__ . '/Repositories' => base_path('/app/Repositories'),
        ], 'repositories');

        $this->publishes([
            __DIR__ . '/Services' => base_path('/app/Services'),
        ], 'services');

        $this->publishes([
            __DIR__ . '/Transformers' => base_path('/app/Transformers'),
        ], 'transformers');

        $this->publishes([
            __DIR__ . '/Http' => base_path('/app/Http'),
        ], 'http');*/
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