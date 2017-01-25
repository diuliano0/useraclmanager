<?php

namespace BetaGT\UserAclManager;

use Illuminate\Contracts\Routing\Registrar as Router;
use Illuminate\Support\Facades\Route;

class RouteRegistrar
{
    /**
     * The router implementation.
     *
     * @var Router
     */
    protected $router;

    /**
     * Create a new route registrar instance.
     *
     * @param  Router  $router
     * @return void
     */
    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    public function all(){
        $this->crudRoutes();
    }

    public static function crudRoutes(){
        Route::group(['prefix'=>'v1'], function () {
            Route::patch('user/password/change', [
                'as' => 'user.alterar_senha',
                'uses' => '\BetaGT\UserAclManager\Http\Controllers\Api\UserController@alterarSenha',
            ]);
            Route::group(['prefix'=>'admin','middleware' => ['auth:api'],'namespace'=>'\BetaGT\UserAclManager\Http\Controllers\Api'],function (){


                Route::group(['middleware'=>['acl'],'is'=>'administrador','protect_alias'=>'user'],function (){
                    Route::get('client/api/revoke/{id}', [
                        'as' => 'user.api_revoke',
                        'uses' => 'ClientTokenController@userRevoke',
                    ]);
                    Route::get('client/api/update_token/{id}', [
                        'as' => 'user.api_revoke',
                        'uses' => 'ClientTokenController@updateToken',
                    ]);
                    Route::resource('client', 'ClientTokenController',
                        [
                            'except' => ['create', 'edit']
                        ]);
                });

                //'is' => 'administrador|moderador,or'
                Route::group(['middleware' => ['acl'],'is' => 'administrador', 'protect_alias'  => 'user'],function (){
                    Route::post('user/password/reset', [
                        'as' => 'user.solicitar_nova_senha',
                        'uses' => 'UserController@solicitarNovaSenha'
                    ]);
                    Route::post('user/password/reset/change', [
                        'as' => 'user.criar_nova_senha',
                        'uses' => 'UserController@criarNovaSenha',
                    ]);
                    Route::resource('user', 'UserController',
                        [
                            'except' => ['create', 'edit']
                        ]);
                    Route::post('user/alterar_imagem', [
                        'as' => 'user.alterar_imagem',
                        'uses' => 'UserController@changeImage',
                    ]);
                    Route::post('user/alterar_imagem_admin/{id}', [
                        'as' => 'user.alterar_imagem',
                        'uses' => 'UserController@changeImageAdmin',
                    ]);
                });
                Route::group(['middleware' => ['acl'],'is' => 'administrador', 'protect_alias'  => 'user'],function (){
                    Route::get('regra/revogar_todas_regras_usuario/{id}', [
                        'as' => 'regra.revogar_todas_regras_usuario',
                        'uses' => 'RoleController@revokeAllRoles'
                    ]);
                    Route::post('regra/sincronizar_regra_permissoes', [
                        'as' => 'regra.sincronizar_regra_permissoes',
                        'uses' => 'RoleController@syncPermissions'
                    ]);
                    Route::post('regra/revogar_regra_usuario', [
                        'as' => 'regra.associar_regra_usuario',
                        'uses' => 'RoleController@revokeRole'
                    ]);
                    Route::post('regra/associar_regra_usuario', [
                        'as' => 'regra.associar_regra_usuario',
                        'uses' => 'RoleController@assocRuleUser'
                    ]);
                    Route::resource('regra', 'RoleController',
                        [
                            'except' => ['create', 'edit']
                        ]);
                    Route::get('regra/regras_do_usuario/{id}', [
                        'as' => 'regra.regras_do_usuario',
                        'uses' => 'RoleController@roleByUser'
                    ]);
                });
                Route::group(['middleware' => ['acl'],'is' => 'administrador', 'protect_alias'  => 'user'],function (){
                    Route::get('permissao/revogar_permissoes/{id}', [
                        'as' => 'permissao.revogar_permissoes',
                        'uses' => 'PermissionController@revokeAllPermissions'
                    ]);
                    Route::get('permissao/lista_de_permissao_regra/{id}', [
                        'as' => 'permissao.lista_de_permissao_regra',
                        'uses' => 'PermissionController@permissionByRole'
                    ]);
                    Route::get('permissao/lista_de_permissao_usuario/{id}', [
                        'as' => 'permissao.lista_de_permissao_usuario',
                        'uses' => 'PermissionController@permissionByUser'
                    ]);
                    Route::get('permissao/list_slugs', [
                        'as' => 'permissao.list_slugs',
                        'uses' => 'PermissionController@listSlugs'
                    ]);
                    Route::post('permissao/associar_permissao_regra', [
                        'as' => 'permissao.associar_permissao_regra',
                        'uses' => 'PermissionController@assocPermissionRole'
                    ]);
                    Route::post('permissao/criar_permissao_usuario', [
                        'as' => 'permissao.criar_permissao_usuario',
                        'uses' => 'PermissionController@assocPermissionUser'
                    ]);
                    Route::resource('permissao', 'PermissionController',[
                        'except' => ['create', 'edit']
                    ]);
                });
            });
        });
    }
}