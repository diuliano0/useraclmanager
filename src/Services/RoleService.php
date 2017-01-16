<?php


namespace BetaGT\UserAclManager\Services;


use BetaGT\UserAclManager\Models\Role;
use BetaGT\UserAclManager\Repositories\RoleRepository;

class RoleService
{
    private $roleRepository;

    public function __construct(RoleRepository $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    public function createAssoc($data){
        $user = \BetaGT\UserAclManager\Models\User::find($data['user_id']);
        return $user->assignRole(Role::find($data['role_id']));
    }
}