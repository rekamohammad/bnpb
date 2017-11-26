<?php

namespace Botble\Base\Supports;

use Botble\ACL\Models\User;
use RuntimeException;
use stdClass;
use URL;

class DashboardMenu
{
    /**
     * Get all registered links from package
     * @var array
     */
    public $links = [];

    /**
     * @var User
     */
    public $user;

    /**
     * @var string
     */
    public $url;

    /**
     * @var string
     */
    public $prefix;

    /**
     * @param User $user
     * @author Sang Nguyen
     */
    public function init(User $user)
    {
        $this->user = $user;
        $this->url = URL::full();
        $this->prefix = '/' . request()->route()->getPrefix();
    }

    /**
     * Add link
     * @param array $options
     * @return $this
     */
    public function registerItem(array $options)
    {

        if (isset($options['children'])) {
            unset($options['children']);
        }
        $defaultOptions = [
            'id' => null,
            'priority' => 99,
            'parent_id' => null,
            'name' => null,
            'icon' => null,
            'url' => null,
            'children' => [],
            'permissions' => [],
        ];
        $options = array_merge($defaultOptions, $options);
        $id = $options['id'];

        if (!$id) {
            $calledClass = isset(debug_backtrace()[1]) ? debug_backtrace()[1]['class'] . '@' . debug_backtrace()[1]['function'] : null;
            throw new RuntimeException('Menu id not specified: ' . $calledClass);
        }

        if (isset($this->links[$id])) {
            $calledClass = isset(debug_backtrace()[1]) ? debug_backtrace()[1]['class'] . '@' . debug_backtrace()[1]['function'] : null;
            throw new RuntimeException('Menu id already exists: ' . $id . ' on class ' . $calledClass);
        }

        $this->links[$id] = $options;

        return $this;
    }

    /**
     * @param array|string $id
     * @return $this
     */
    public function removeItem($id)
    {
        $id = is_array($id) ? $id : func_get_args();
        foreach ($id as $item) {
            array_forget($this->links, $item);
        }

        return $this;
    }

    /**
     * Get children items
     * @param null $id
     * @return \Illuminate\Support\Collection
     */
    public function getChildren($id = null)
    {
        $children = collect([]);
        foreach ($this->links as $key => $row) {
            if (!$this->user->hasAnyPermission(array_get($row, 'permissions'))) {
                continue;
            }
            $child = new stdClass();
            $child->id = $row['id'];
            $child->priority = $row['priority'];
            $child->parent_id = $row['parent_id'];
            $child->name = $row['name'];
            $child->icon = $row['icon'];
            $child->url = $row['url'];
            $child->children = $row['children'];
            $child->permissions = $row['permissions'];
            $child->active = false;

            if ($child->parent_id == $id) {
                $child->children = $this->getChildren($child->id);

                $children->push($child);
            }
        }
        return $children->sortBy('priority');
    }

    /**
     * Rearrange links
     * @return \Illuminate\Support\Collection
     */
    public function getAll()
    {
        if (config('cms.enable_cache_dashboard_menu')) {
            $cache_key = md5('cache-dashboard-menu');
            if (!cache()->has($cache_key)) {
                $links = collect($this->getChildren())->sortBy('priority');
                cache()->forever($cache_key, $links);
            } else {
                $links = cache()->get($cache_key);
            }
        } else {
            $links = collect($this->getChildren())->sortBy('priority');
        }
        foreach ($links as $link) {
            $link->active = $this->url == $link->url || str_contains($link->url, $this->prefix);
            if (isset($link->children)) {
                foreach ($link->children as $sub_menu) {
                    if ($this->url == $sub_menu->url || str_contains($sub_menu->url, $this->prefix)) {
                        $sub_menu->active = true;
                        $link->active = true;
                        break;
                    }
                }
            }
        }
        return $links;
    }
}
