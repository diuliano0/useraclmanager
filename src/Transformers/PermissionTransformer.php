<?php

namespace BetaGT\UserAclManager\Transformers;

use League\Fractal\TransformerAbstract;
use BetaGT\UserAclManager\Models\Permission;

/**
 * Class PermissionTransformer
 * @package namespace BetaGT\UserAclManager\Transformers;
 */
class PermissionTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['roles','users'];

    /**
     * Transform the \Permission entity
     * @param \Permission $model
     *
     * @return array
     */
    public function transform(Permission $model)
    {
        return [
            'id'           => (int) $model->id,
            'name'         => (string) $model->name,
            'slug'         => $model->slug,
            'description'  => (string) $model->description,

            /* place your other model properties here */

            'created_at' => $model->created_at,
            'updated_at' => $model->updated_at
        ];
    }

    public function includeRoles(Permission $model)
    {
        if (!$model->return_rules)
        {
            return null;
        }
        return $this->collection($model->return_rules, new RoleTransformer());
    }

    public function includeUsers(Permission $model)
    {
        if (!$model->user_permissions)
        {
            return null;
        }
        return $this->collection($model->user_permissions, new UserTransformer());
    }
}
