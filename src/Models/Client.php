<?php

namespace BetaGT\UserAclManager\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use Laravel\Passport\Client as PassportClient;

class Client extends PassportClient implements Transformable
{
    use TransformableTrait,Auditable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'oauth_clients';

}
