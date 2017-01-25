<?php

namespace BetaGT\UserAclManager\Criteria;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class RoleCriteria
 * @package namespace BetaGT\UserAclManager\Criteria;
 */
class RoleCriteria extends BaseCriteria implements CriteriaInterface
{
    protected $filterCriteria = [
        'roles.id'=>'=',
        'roles.name'=>'like',
        'roles.created_at'=>'between',
        'roles.updated_at'=>'between',
    ];
}
