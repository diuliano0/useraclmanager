<?php

namespace BetaGT\UserAclManager\Repositories;

use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface UserRepository
 * @package namespace BetaGT\UserAclManager\Repositories;
 */
interface UserRepository extends RepositoryInterface
{
    /**
     * Get all roles.
     *
     * @return array
     */
    public function getRoles(int $id);

    /**
     * @param int $id
     * @return mixed
     */
    public function getPermissions(int $id);

    /**
     * @param string $alias
     * @param array|null $slugs
     * @return mixed
     */
    public function removePermission(string $alias, array $slugs = null);

    /**
     * @param string $alias
     * @param array|null $slugs
     * @return mixed
     */
    public function addPermission(int $userId,string $alias, array $slugs = null);

    /**
     * Revoke Role(s) From User
     *
     * Similarly, you may revoke roles from user
     *
     * @param int $id
     * @param int|string|collection|array $role
     * @return mixed
     */
    public function revokeRole(int $id, $role);

    /**
     * Revoke All User Roles
     *
     * You can revoke all roles assigned to a user.
     *
     * @param int $id
     * @param int|string|collection|array $role
     * @return mixed
     */
    public function revokeAllRoles(int $id);

    /**
     * Sync Role(s) To User
     *
     * You can pass an array of role objects,ids or slugs to sync them to a user.
     *
     * @param int $userId
     * @param array $roles
     * @return mixed
     */
    public function syncRoles(int $userId, $roles);
}
