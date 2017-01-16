<?php

namespace BetaGT\UserAclManager\Transformers;

use League\Fractal\TransformerAbstract;
use BetaGT\UserAclManager\Models\User;

/**
 * Class UserTransformer
 * @package namespace BetaGT\UserAclManager\Transformers;
 */
class UserTransformer extends BaseTransformer
{
    public $availableIncludes = ['permissions','roles'];
    /**
     * Transform the \User entity
     * @param \User $model
     *
     * @return array
     */
    public function transform(User $model)
    {
        return [
            'id'               => (int) $model->id,
            'nome'             => (string) $model->name,
            'email'            => (string) $model->email,
            'email_alternativo'=> (string) $model->email_alternativo,
            'sexo_label'=> (string) User::$_SEXO[$model->sexo],
            'sexo'=> (int) $model->sexo,
            'imagem'=> (string) $model->imagem,
            'chk_newsletter'=> (boolean) $model->chk_newsletter,

            /* place your other model properties here */

            'created_at'       => $model->created_at,
            'updated_at'       => $model->updated_at
        ];
    }

    public function includePermissions(User $model)
    {
        if (!$model->permissions)
        {
            return null;
        }
        return $this->collection($model->permissions, new PermissionTransformer());
    }

    public function includeRoles(User $model)
    {
        if (!$model->return_roles)
        {
            return null;
        }
        return $this->collection($model->return_roles, new RoleTransformer());
    }
}
