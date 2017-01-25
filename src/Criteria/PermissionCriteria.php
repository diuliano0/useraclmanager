<?php

namespace BetaGT\UserAclManager\Criteria;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class PermissionCriteria
 * @package namespace BetaGT\UserAclManager\Criteria;
 */
class PermissionCriteria extends BaseCriteria implements CriteriaInterface
{
    protected $filterCriteria = [
        'permissions.id'=>'=',
        'permissions.name'=>'like',
        'permissions.created_at'=>'between',
        'permissions.updated_at'=>'between',
    ];
}
