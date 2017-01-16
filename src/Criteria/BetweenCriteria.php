<?php

namespace BetaGT\UserAclManager\Criteria;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class BetweenCriteria
 * @package namespace BetaGT\UserAclManager\Criteria;
 */
class BetweenCriteria implements CriteriaInterface
{
    /**
     * @var Request
     */
    private $request;
    /**
     * @var string
     */
    private $field;

    public function __construct(string $field,Request $request)
    {
        $this->request = $request;
        $this->field = $field;
    }

    /**
     * Apply criteria in query repository
     *
     * @param       Model              $model
     * @param RepositoryInterface $repository
     *
     * @return mixed
     */
    public function apply($model, RepositoryInterface $repository)
    {
        $date = $this->request->get($this->field);
        if(is_null($date)){
            return $model;
        }

        return $model->whereBetween($this->field, [$date[0], $date[1]]);
    }
}
