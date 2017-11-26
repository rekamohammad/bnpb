<?php

namespace Botble\ACL\Models;

use Eloquent;

class PermissionFlag extends Eloquent
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'permission_flags';

    /**
     * Disable automatic handling of timestamps.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'flag',
        'name',
        'parent_flag',
        'is_feature',
        'permission_visible',
        'feature_visible',
    ];

    /**
     * This is to cleanly add an extra permission flags to the system
     * @param $args
     * @return \Illuminate\Database\Eloquent\Model | boolean
     * @author Sang Nguyen
     */
    public static function createNewPermissionFlag($args = [])
    {
        $newFlag = array_get($args, 'flag');
        $newFlagName = array_get($args, 'name');
        $isFeatured = array_get($args, 'is_feature', false);
        $parentFlag = array_get($args, 'parent_flag', 0);
        if ($parentFlag) {
            $parentFlagRecord = PermissionFlag::where('flag', $parentFlag)->first();
            if (!$parentFlagRecord) {
                return false;
            }
            $parentFlag = $parentFlagRecord->id;
        }

        $newFlag = PermissionFlag::firstOrCreate([
            'flag' => $newFlag,
        ], [
            'name' => $newFlagName,
            'is_feature' => $isFeatured,
            'parent_flag' => $parentFlag,
        ]);
        return $newFlag;
    }
}
