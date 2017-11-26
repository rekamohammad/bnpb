<?php

namespace Botble\Base\Commands;

use Botble\ACL\Repositories\Interfaces\UserInterface;
use DB;
use Illuminate\Console\Command;

class RebuildPermissionsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cms:rebuild_permissions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rebuild all the user permissions from the users defined roles and the roles defined flags';

    /**
     * RebuildPermissions constructor.
     * @author Sang Nguyen
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     * @param $return
     * @author Sang Nguyen
     */
    public function handle($return = false)
    {
        // Safety first!

        DB::beginTransaction();

        // Remove flags from roles if the flags have been deleted from the system
        DB::delete('DELETE FROM role_flags WHERE flag_id NOT IN (SELECT id FROM permission_flags)');
        // Remove roles from ... roles ... if the ... roles ... have been deleted from the system
        DB::delete('DELETE FROM role_flags WHERE role_id NOT IN (SELECT id FROM roles)');

        // Firstly, lets grab out the global roles
        $allRoles = DB::select('SELECT id, name FROM roles');

        if (empty($allRoles)) {
            $users = app(UserInterface::class)->all();
            foreach ($users as $user) {
                $user->permissions = [
                    'superuser' => $user->super_user ? true : false,
                    'manage_supers' => $user->manage_supers ? true : false,
                ];
                app(UserInterface::class)->createOrUpdate($user);
            }
        } else {
            // Go and grab all of the permission flags defined on these global roles
            foreach ($allRoles as $role) {
                // Grab all of the
                $rolePermissionFlags = DB::select('SELECT flag FROM permission_flags WHERE id IN (SELECT flag_id FROM role_flags WHERE role_id=' . $role->id . ')');

                $permissions = [];
                foreach ($rolePermissionFlags as $rolePermissionFlag) {
                    $permissions[$rolePermissionFlag->flag] = true;

                }

                $userRoles = DB::select('SELECT user_id, role_id FROM role_users WHERE role_id=' . $role->id);
                foreach ($userRoles as $userRole) {
                    // Insert permission flag
                    $user_permissions = [];
                    $user = DB::select('SELECT super_user, manage_supers FROM users WHERE id=' . $userRole->user_id);
                    if (!empty($user)) {
                        $user = $user[0];
                        $user_permissions['superuser'] = $user->super_user ? true : false;
                        $user_permissions['manage_supers'] = $user->manage_supers ? true : false;
                        DB::statement("UPDATE users SET permissions = '" . json_encode(array_merge($permissions, $user_permissions)) . "' where id=" . $userRole->user_id);
                    }
                }
            }
        }

        if (!$return) {
            echo 'Rebuild user permissions successfully!' . PHP_EOL;
        }

        DB::commit();
    }
}
