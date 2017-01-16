<?php

namespace BetaGT\UserAclManager\Repositories;

use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface RoleRepository
 * @package namespace BetaGT\UserAclManager\Repositories;
 */
interface RoleRepository extends RepositoryInterface
{
    /**
     * @param int $id
     * @return mixed
     */
    public function getPermissions(int $id);

    /**
     *
     * @param int $roleId
     * @param int|string|collection $permUser
     * @return mixed
     */
    public function assignPermission(int $roleId,$permUser);

    /**
     *
     * @param int $roleId
     * @param int|string|collection|array $permUser
     * @return mixed
     */
    public function revokePermission(int $roleId,$permUser);

    /**
     * @param int $id
     * @return mixed
     */
    public function revokeAllPermissions(int $id);

    /**
     * @param int $roleId
     * @param array $permissions
     * @return mixed
     */
    public function syncPermissions(int $roleId,array $permissions);
}
