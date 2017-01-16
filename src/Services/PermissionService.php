<?php
/**
 * Created by PhpStorm.
 * User: dsoft
 * Date: 06/01/2017
 * Time: 13:50
 */

namespace BetaGT\UserAclManager\Services;


use BetaGT\UserAclManager\Models\Permission;
use BetaGT\UserAclManager\Models\Role;
use BetaGT\UserAclManager\Models\User;
use BetaGT\UserAclManager\Repositories\PermissionRepository;
use BetaGT\UserAclManager\Repositories\RoleRepository;
use BetaGT\UserAclManager\Repositories\UserRepository;

class PermissionService
{
    /**
     * @var PermissionRepository
     */
    private $permissionRepository;
    /**
     * @var RoleRepository
     */
    private $roleRepository;

    const defaltSlug = [
        "store",
        "show",
        "update",
        "delete",
        "index"
    ];
    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(PermissionRepository $permissionRepository, RoleRepository $roleRepository,UserRepository $userRepository)
    {
        $this->permissionRepository = $permissionRepository;
        $this->roleRepository = $roleRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * @param int $roleId
     * @param int $permissionId
     * @return mixed
     */
    public function assocPermissionRole(int $roleId, $permUser){
        return $this->roleRepository->assignPermission($roleId, $permUser);
    }

    /**
     * @param $data
     * @return mixed
     */
    public function assocPermissionUser($data){
        $rs = $this->userRepository->addPermission($data['user_id'],$data['name'],$data['slug']);
        if( !$rs){
            abort(422,'Alias ja esite');
        }
        return $rs;
    }
}