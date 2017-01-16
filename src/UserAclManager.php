<?php

namespace BetaGT\UserAclManager;

/**
 * Created by PhpStorm.
 * User: dsoft
 * Date: 16/01/2017
 * Time: 09:22
 */
class UserAclManager
{
    public static function routes(){
        RouteRegistrar::crudRoutes();
    }
}