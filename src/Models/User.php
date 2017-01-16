<?php

namespace BetaGT\UserAclManager\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Kodeine\Acl\Traits\HasRole;
use Laravel\Passport\HasApiTokens;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class User extends Authenticatable implements Transformable
{
    use Notifiable,HasApiTokens,SoftDeletes,TransformableTrait,HasRole;

    protected $fieldSearchable = [
        'name'=>'like',
        'email'=>'like',
        'sexo',
    ];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email',  'password','email_alternativo', 'sexo', 'imagem', 'chk_newsletter'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $dates = ['deleted_at'];

    public static $_SEXO = [
            1=>'masculino',
            2=>'feminino'
        ];


    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'permission_user', 'user_id', 'permission_id');
    }
    public function return_roles()
    {
        return $this->belongsToMany(Role::class, 'role_user', 'user_id', 'role_id');
    }

}
