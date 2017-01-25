<?php

namespace BetaGT\UserAclManager\Repositories;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use BetaGT\UserAclManager\Presenters\ClientPresenter;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use BetaGT\UserAclManager\Repositories\ClientRepository;
use BetaGT\UserAclManager\Models\Client;
use Laravel\Passport\Client as LClient;
use BetaGT\UserAclManager\Validators\ClientValidator;

/**
 * Class ClientRepositoryEloquent
 * @package namespace Portal\Repositories;
 */
class ClientRepositoryEloquent extends BaseRepository implements ClientRepository
{
    /**
     * Specify Model class name
     *
     * @return Client
     */
    public function model()
    {
        return Client::class;
    }
    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    public function presenter()
    {
        return ClientPresenter::class;
    }


    public function findActive($id)
    {
        return $this->model->where('user_id', $id)
            ->orderBy('name', 'desc')->get();
    }

    public function forUser($userId)
    {
        // TODO: Implement forUser() method.
    }

    public function activeForUser($userId)
    {
        // TODO: Implement activeForUser() method.
    }

    public function personalAccessClient()
    {
        // TODO: Implement personalAccessClient() method.
    }

    public function createPersonalAccessClient($userId, $name, $redirect)
    {
        // TODO: Implement createPersonalAccessClient() method.
    }

    public function createPasswordGrantClient($userId, $name, $redirect)
    {
        return $this->create($userId, $name, $redirect, false, true);
    }

    public function regenerateSecret($clientId)
    {
        $client = $this->model->find($clientId);

        if(!$client)
            throw new ModelNotFoundException(trans('errors.sql_state.registre_not_found'));
        $client->forceFill([
            'secret' => str_random(40),
        ])->save();
        return $this->parserResult($client);
    }

    public function revoked($id)
    {
        return $this->model->where('id', $id)
            ->where('revoked', true)->exists();
    }

    public function revogarTokens($clientId)
    {
        $client = $this->model->find($clientId);
        if(!$client)
            throw new ModelNotFoundException(trans('errors.sql_state.registre_not_found'));

        $client->tokens()->update(['revoked' => true]);
        $client->forceFill(['revoked' => true])->save();
    }

}
