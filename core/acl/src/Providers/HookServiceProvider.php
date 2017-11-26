<?php

namespace Botble\ACL\Providers;

use Botble\ACL\Repositories\Interfaces\UserInterface;
use Illuminate\Support\ServiceProvider;

class HookServiceProvider extends ServiceProvider
{
    /**
     * @author Sang Nguyen
     */
    public function boot()
    {
        add_filter(DASHBOARD_FILTER_TOP_BLOCKS, [$this, 'addUserStatsWidget'], 12, 1);
    }

    /**
     * @param $widgets
     * @return string
     * @author Sang Nguyen
     */
    public function addUserStatsWidget($widgets)
    {
        $users = app(UserInterface::class)->count();

        return $widgets . view('acl::partials.widgets.user-stats', compact('users'))->render();
    }
}
