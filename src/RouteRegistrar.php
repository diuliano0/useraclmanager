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
            Route::group(['prefix'=>'admin','middleware' => ['auth:api'],'namespace'=>'\BetaGT\UserAclManager\Http\Controllers\Api'],function (){

                Route::patch('user/password/change', [
                    'as' => 'user.alterar_senha',
                    'uses' => 'UserController@alterarSenha',
                ]);

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
            });
        });
    }
}