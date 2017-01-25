<?php

namespace BetaGT\UserAclManager\Criteria;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class ClientCriteria
 * @package namespace Portal\Criteria;
 */
class ClientCriteria extends BaseCriteria implements CriteriaInterface
{
    protected $filterCriteria = [
        'oauth_clients.id'=>'=',
        'oauth_clients.user_id'=>'=',
        'oauth_clients.name'=>'like',
        'oauth_clients.personal_access_client'=>'=',
        'oauth_clients.password_client'=>'=',
        'oauth_clients.revoked'=>'=',
        'oauth_clients.created_at'=>'between',
        'oauth_clients.updated_at'=>'between',
    ];
}
