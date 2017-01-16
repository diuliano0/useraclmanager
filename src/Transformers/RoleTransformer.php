<?php

namespace BetaGT\UserAclManager\Transformers;

use League\Fractal\TransformerAbstract;
use BetaGT\UserAclManager\Models\Role;

/**
 * Class RoleTransformer
 * @package namespace BetaGT\UserAclManager\Transformers;
 */
class RoleTransformer extends TransformerAbstract
{
    public $availableIncludes = ['permissions'];

    /**
     * Transform the \Role entity
     * @param \Role $model
     *
     * @return array
     */
    public function transform(Role $model)
    {
        return [
            'id'        => (int) $model->id,
            'name'      => (string) $model->name,
            'slug'      => (string) $model->slug,
            'description'         => (string) $model->description,
            /* place your other model properties here */

            'created_at' => $model->created_at,
            'updated_at' => $model->updated_at
        ];
    }

    public function includePermissions(Role $model)
    {
        if (!$model->permissions)
        {
            return null;
        }
        return $this->collection($model->permissions, new PermissionTransformer());
    }
}
