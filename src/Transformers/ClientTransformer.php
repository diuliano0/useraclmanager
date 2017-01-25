<?php

namespace BetaGT\UserAclManager\Transformers;

use League\Fractal\TransformerAbstract;
use BetaGT\UserAclManager\Models\Client;

/**
 * Class ClientTransformer
 * @package namespace Portal\Transformers;
 */
class ClientTransformer extends TransformerAbstract
{

    /**
     * Transform the \Client entity
     * @param \Client $model
     *
     * @return array
     */
    public function transform(Client $model)
    {
        return [
            'id'         => (int) $model->id,
            'user_id'    =>(int) $model->user_id,
            'nome'       => (string) $model->name,
            'personal_access_client'=>(boolean) $model->personal_access_client,
            'password_client'       => (boolean) $model->password_client,
            'redirect'               => (string) $model->redirect,
            'secret'               => (string) $model->secret,
            'created_at' => $model->created_at,
            'updated_at' => $model->updated_at
        ];
    }
}
