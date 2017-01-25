<?php

namespace BetaGT\UserAclManager\Criteria;

use Illuminate\Http\Request;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class UserCriteria
 * @package namespace BetaGT\UserAclManager\Criteria;
 */
class UserCriteria extends BaseCriteria implements CriteriaInterface
{
    protected $filterCriteria = [
        'users.name'=>'like',
        'users.email'=>'=',
        'users.sexo'=>'=',
        'users.created_at'=>'between',
    ];

    public function apply($model, RepositoryInterface $repository)
    {
        $model = parent::apply($model, $repository);
        return $model;
    }
}
