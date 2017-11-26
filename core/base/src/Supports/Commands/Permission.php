<?php

namespace Botble\Base\Supports\Commands;

use Botble\ACL\Models\Feature;
use Botble\ACL\Models\PermissionFlag;
use Botble\Base\Commands\RebuildPermissionsCommand;

class Permission
{
    /**
     * @param $permissions
     * @author Sang Nguyen
     */
    public static function registerPermission($permissions)
    {
        foreach ($permissions as $permission) {
            $permission = PermissionFlag::createNewPermissionFlag($permission);
            if (!empty($permission)) {
                if ($permission->is_feature) {
                    $feature = new Feature();
                    $feature->firstOrCreate(['feature_id' => $permission->id]);
                }
            }
        }
        $permissionRebuild = new RebuildPermissionsCommand();
        $permissionRebuild->handle(true);
    }

    /**
     * @param $permissions
     * @author Sang Nguyen
     */
    public static function removePermission($permissions)
    {
        foreach ($permissions as $permission) {
            $permission = PermissionFlag::where('flag', '=', $permission['flag'])->first();
            if ($permission) {
                if ($permission->is_feature) {
                    Feature::where('feature_id', '=', $permission->id)->forceDelete();
                }
                $permission->forceDelete();
            }
        }
        $permissionRebuild = new RebuildPermissionsCommand();
        $permissionRebuild->handle(true);
    }
}