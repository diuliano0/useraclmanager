<?php
/**
 * Created by PhpStorm.
 * User: dsoft
 * Date: 04/01/2017
 * Time: 11:36
 */

namespace BetaGT\UserAclManager\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use \Kodeine\Acl\Models\Eloquent\Role as Nrole;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class Role extends Nrole implements Transformable
{
    use SoftDeletes,TransformableTrait;

    protected $dates = ['deleted_at'];

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'permission_role', 'role_id', 'permission_id');
    }
}