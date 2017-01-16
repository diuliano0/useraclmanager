<?php
/**
 * Created by PhpStorm.
 * User: dsoft
 * Date: 13/01/2017
 * Time: 11:51
 */

namespace BetaGT\UserAclManager\Services;


class CacheService
{
    private $cache;

    public function __construct(\Illuminate\Cache\Repository $cache )
    {
        $this->cache = $cache;
    }

    public function getCache(){
        return $this->cache;
    }

}