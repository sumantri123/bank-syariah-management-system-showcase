<?php

namespace App\Traits;
use App\Models\Role;use App\Models\Modul;
trait HasModulTrait
{
  public function assignRole(...$roles)
  {    // ambil model $roles    $roles = $this->getAllRoles(array_flatten($roles));
    if($roles == null) {
      return $this;
    }
    $this->roles()->saveMany($roles);
    return $this;
    // simpan
  }
  public function removeRole(...$roles)
  {
    $roles = $this->getAllRoles($roles);

    $this->roles()->detach($roles);

    return $this;
  }

  public function syncRoles(...$roles)
  {
    $this->roles()->detach();

    return $this->assignRole($roles);
  }

  public function givePermissionTo(...$permissions)
  {

    $permissions = $this->getAllPermissions(array_flatten($permissions));

    if($permissions == null){
      return $this;
    }

    $this->permissions()->saveMany($permissions);

    return $this;
    // ambil model permissions
    // save may
  }

  public function revokePermission(...$permissions)
  {
    $permissions = $this->getAllPermissions($permissions);

    $this->permissions()->detach($permissions);

    return $this;
  }

  public function updatePermissions(...$permissions)
  {
    $this->permissions()->detach();

    return $this->givePermissionTo($permissions);
  }

  public function HasPermissionTo($permission)
  {
    // permissions berdasarkan roles
    // return $this->HasPermission($permission);
    return $this->HasPermissionThroughRole($permission) || $this->HasPermission($permission);
  }

  protected function getAllRoles(array $roles)
  {
    return Role::whereIn('name',$roles)->get();
  }
  protected function getAllPermissions(array $permissions)
  {
    return Permission::whereIn('name',$permissions)->get();
  }

  protected function HasPermissionThroughRole($permission)
  {
    foreach ($permission->roles as $role) {
      if($this->roles->contains($role)){
        return true;
      }
    }

    return false;
  }

  protected function HasPermission($permission)
  {
    return (bool) $this->permissions->where('name', $permission->name)->count();
      // return $permission;
  }

  public function hasRole(...$roles)
  {
      foreach ($roles as $role) {
        if($this->roles->contains('nama_role',$role)){
          return true;
        }
      }

      return false;
  }
 