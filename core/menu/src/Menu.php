<?php

namespace Botble\Menu;

use Botble\Menu\Models\MenuNode;
use Botble\Menu\Repositories\Eloquent\MenuRepository;
use Botble\Menu\Repositories\Interfaces\MenuContentInterface;
use Botble\Menu\Repositories\Interfaces\MenuInterface;
use Botble\Menu\Repositories\Interfaces\MenuNodeInterface;
use Botble\Support\Services\Cache\Cache;
use Collective\Html\HtmlBuilder;
use Exception;
use Schema;
use Theme;

class Menu
{
    /**
     * @var mixed
     */
    protected $menuRepository;

    /**
     * @var HtmlBuilder
     */
    protected $html;

    /**
     * @var MenuContentInterface
     */
    protected $menuContentRepository;

    /**
     * @var MenuNodeInterface
     */
    protected $menuNodeRepository;

    /**
     * @var Cache
     */
    protected $cache;

    /**
     * Menu constructor.
     * @param MenuInterface $menu
     * @param HtmlBuilder $html
     * @param MenuContentInterface $menuContentRepository
     * @param MenuNodeInterface $menuNodeRepository
     * @param Cache $cache
     * @author Sang Nguyen
     */
    public function __construct(
        MenuInterface $menu,
        HtmlBuilder $html,
        MenuContentInterface $menuContentRepository,
        MenuNodeInterface $menuNodeRepository
    ) {
        $this->menuRepository = $menu;
        $this->html = $html;
        $this->menuContentRepository = $menuContentRepository;
        $this->menuNodeRepository = $menuNodeRepository;
        $this->cache = new Cache(app('cache'), MenuRepository::class);
    }

    /**
     * @param $args
     * @return mixed|null|string
     * @author Sang Nguyen, Tedozi Manson
     */
    public function generateMenu($args = [])
    {
        $slug = array_get($args, 'slug');
        if (!$slug) {
            return null;
        }

        $view = array_get($args, 'view');
        $theme = array_get($args, 'theme', true);

        $cache_key = md5('cache-menu-' . serialize($args));
        if (!$this->cache->has($cache_key)) {
            $parent_id = array_get($args, 'parent_id', 0);
            $active = array_get($args, 'active', true);
            $options = $this->html->attributes(array_get($args, 'options', []));

            $menu = $this->menuRepository->findBySlug($slug, $active, ['menus.id', 'menus.slug']);

            if (!$menu) {
                return null;
            }

            $menuContent = $this->menuContentRepository->getFirstBy(['menu_id' => $menu->id]);
            if (!$menuContent) {
                $menu_nodes = [];
            } else {
                $menu_nodes = $this->menuNodeRepository->getByMenuContentId($menuContent->id, $parent_id, [
                    'id',
                    'menu_content_id',
                    'parent_id',
                    'related_id',
                    'icon_font',
                    'css_class',
                    'target',
                    'url',
                    'title',
                    'type',
                ]);
            }

            $data = compact('menu_nodes', 'menu');
            $this->cache->put($cache_key, $data);

        } else {
            $data = $this->cache->get($cache_key);
            $options = $this->html->attributes(array_get($args, 'options', []));
        }

        $data['options'] = $options;

        if ($theme && $view) {
            return Theme::partial($view, $data);
        } elseif ($view) {
            return view($view, $data)->render();
        } else {
            return view('menu::partials.default', $data)->render();
        }
    }

    /**
     * @param array $args
     * @return mixed|null|string
     * @author Sang Nguyen, Tedozi Manson
     */
    public function generateSelect($args = [])
    {
        $model = array_get($args, 'model');
        if (!$model) {
            return null;
        }

        $view = array_get($args, 'view');
        $theme = array_get($args, 'theme', true);

        $cache_key = md5('cache-menu-' . serialize($args));
        if (!$this->cache->has($cache_key) || true) {
            $parent_id = array_get($args, 'parent_id', 0);
            $active = array_get($args, 'active', true);
            $options = $this->html->attributes(array_get($args, 'options', []));

            if (Schema::hasColumn($model->getTable(), 'parent_id')) {
                $object = $model->whereParentId($parent_id)->orderBy('name', 'asc');
            } else {
                $object = $model->orderBy('name', 'asc');
            }
            if ($active) {
                $object = $object->where('status', $active);
            }
            $object = $object->get();

            if (empty($object)) {
                return null;
            }

            $data = compact('object', 'model', 'options');

            $this->cache->put($cache_key, $data);
        } else {
            $data = $this->cache->get($cache_key);
        }

        if ($theme && $view) {
            return Theme::partial($view, $data);
        } elseif ($view) {
            return view($view, $data)->render();
        } else {
            return view('menu::partials.select', $data)->render();
        }
    }

    /**
     * @param $slug
     * @param $active
     * @return bool
     * @author Sang Nguyen
     */
    public function hasMenu($slug, $active)
    {
        $menu = $this->menuRepository->findBySlug($slug, $active);
        if (!$menu) {
            return false;
        }
        return true;
    }

    /**
     * @param $menu_nodes
     * @param $menu_content_id
     * @param $parent_id
     * @author Sang Nguyen, Tedozi Manson
     */
    public function recursiveSaveMenu($menu_nodes, $menu_content_id, $parent_id)
    {
        try {
            foreach ($menu_nodes as $row) {
                $parent = $this->saveMenuNode($row, $menu_content_id, $parent_id);
                if (!empty($parent)) {
                    $this->recursiveSaveMenu(array_get($row, 'children'), $menu_content_id, $parent);
                }
            }
        } catch (Exception $ex) {
            info($ex->getMessage());
        }
    }

    /**
     * @param $menu_item
     * @param $menu_content_id
     * @param $parent_id
     * @return mixed
     * @author Sang Nguyen, Tedozi Manson
     */
    protected function saveMenuNode($menu_item, $menu_content_id, $parent_id)
    {
        $item = MenuNode::find(array_get($menu_item, 'id'));
        if (!$item) {
            $item = new MenuNode();
        }

        $item->title = array_get($menu_item, 'title');
        $item->url = array_get($menu_item, 'customUrl');
        $item->css_class = array_get($menu_item, 'class');
        $item->position = array_get($menu_item, 'position');
        $item->icon_font = array_get($menu_item, 'iconFont');
        $item->target = array_get($menu_item, 'target');
        $item->type = array_get($menu_item, 'type');
        $item->menu_content_id = $menu_content_id;
        $item->parent_id = $parent_id;

        switch ($item->type) {
            case 'custom-link':
                $item->related_id = 0;
                break;
            default:
                $item->related_id = (int)array_get($menu_item, 'relatedId');
                break;
        }
        $this->menuNodeRepository->createOrUpdate($item);

        return $item->id;
    }
}
