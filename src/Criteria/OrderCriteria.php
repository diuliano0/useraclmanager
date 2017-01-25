<?php

namespace BetaGT\UserAclManager\Criteria;

use Illuminate\Database\Eloquent\Builder;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class OrderCriteria
 * @package namespace BetaGT\UserAclManager\Criteria;
 */
class OrderCriteria extends BaseCriteria implements CriteriaInterface
{
    /**
     * Apply criteria in query repository
     *
     * @param                     $model
     * @param RepositoryInterface $repository
     *
     * @return mixed
     */
    public function apply($model, RepositoryInterface $repository)
    {
        if(is_null($this->whereArray)){
            return $model;
        }
        if(!($model instanceof Builder)){
            $model->select($model->getTable().'.*');
        }
        if(array_key_exists('order',$this->whereArray)){
            $order = explode(';',$this->whereArray['order']);
            if(count($order)!=2)
                return $model;
            return $this->builderOrderBy($model,$order[0],$order[1]);
        }
        return $model;
    }
}
