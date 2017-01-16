<?php
/**
 * Created by PhpStorm.
 * User: dsoft
 * Date: 04/01/2017
 * Time: 11:39
 */

namespace BetaGT\UserAclManager\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use \Kodeine\Acl\Models\Eloquent\Permission as Npermission;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class Permission extends Npermission implements Transformable
{
    use SoftDeletes,TransformableTrait;

    protected $dates = ['deleted_at'];


    public function return_rules()
    {
        return $this->belongsToMany(Role::class, 'permission_role', 'role_id', 'permission_id');
    }

    public function user_permissions()
    {
        return $this->belongsToMany(User::class, 'permission_user', 'user_id', 'permission_id');
    }
}