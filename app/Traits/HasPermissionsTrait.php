<?php

namespace App\Traits;

use phpDocumentor\Reflection\Types\Boolean;
use App\Models\Role;
use App\Models\Permission;

/**
 * Access Control List for users
 * Trait HasPermissionsTrait
 * @package App\Permissions
 */
trait HasPermissionsTrait
{
    /**
     *
     * Give permissions to user
     * @param mixed ...$permissions
     * @return $this
     */
    public function givePermissionsTo(... $permissions) {

        $permissions = $this->getAllPermissions($permissions);

        if($permissions === null) {
            return $this;
        }

        $this->permissions()->saveMany($permissions);

        return $this;
    }

    /**
     * @param mixed ...$permissions
     * @return $this
     */
    public function withdrawPermissionsTo( ... $permissions )
    {
        $permissions = $this->getAllPermissions($permissions);
        $this->permissions()->detach($permissions);
        return $this;
    }

    /**
     * @param mixed ...$permissions
     * @return HasPermissionsTrait
     */
    public function refreshPermissions( ... $permissions )
    {
        $permissions = $this->checkBeforeInsert($permissions);
        $this->permissions()->detach();
        return $this->givePermissionsTo($permissions);
    }

    /**
     * @param $permission
     * PermissionServiceProvider passes to gate the permission collection
     * @return bool
     */
    public function hasPermissionTo($permission)
     {
        return $this->hasPermissionThroughRole($permission) || $this->hasPermission($permission);
    }

    /**
     * @param $permission
     * PermissionServiceProvider passes to gate the permission collection
     * @return bool
     */
    public function hasPermissionThroughRole($permission): bool
    {

        foreach ($permission->roles as $role){
            if($this->roles->contains($role)) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param mixed ...$roles
     * @return bool
     */
    public function hasRole( ... $roles ) : bool
    {

        foreach ($roles as $role) {
            if ($this->roles->contains('slug', $role)) {
                return true;
            }
        }
        return false;
    }

    /**
     * @return mixed
     * Check users roles
     */
    public function roles()
    {

        return $this->belongsToMany(Role::class);

    }

    /**
     * Check users permissions
     * @return mixed
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }

    /**
     * @param $permission
     * PermissionServiceProvider passes to gate the permission collection
     * @return bool
     */
    protected function hasPermission($permission): bool
    {
        return (bool) $this->permissions->where('slug', $permission->slug)->count();
    }

    /**
     * @param array $permissions
     * @return mixed
     */
    protected function getAllPermissions(array $permissions)
    {
        $permissions = $this->checkBeforeInsert($permissions);
        return Permission::whereIn('slug',$permissions)->get();

    }

    /**
     * Splate operator when chaining doubles up the array so return first value
     * @param array $permissions
     * @return array
     */
    protected function checkBeforeInsert(array $permissions) : array
    {
        if(count($permissions) == 1)
        {
            if(is_array($permissions[0])) {
                return $permissions[0];
            }
        }

        return $permissions;
    }
}
