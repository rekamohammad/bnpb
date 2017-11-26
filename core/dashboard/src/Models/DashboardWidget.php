<?php

namespace Botble\Dashboard\Models;

use Eloquent;

class DashboardWidget extends Eloquent
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'dashboard_widgets';

    /**
     * The date fields for the model.clear
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at'];

    /**
     * @var array
     */
    protected $fillable = ['name'];

    /**
     * @return mixed
     * @author Sang Nguyen
     */
    public function userSetting()
    {
        return $this->settings()->where('user_id', '=', acl_get_current_user_id())->select(['status']);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     * @author Sang Nguyen
     */
    public function settings()
    {
        return $this->hasMany(DashboardWidgetSetting::class, 'widget_id', 'id');
    }
}
