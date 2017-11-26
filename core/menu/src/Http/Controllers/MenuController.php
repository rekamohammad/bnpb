<?php

namespace Botble\Menu\Http\Controllers;

use Artisan;
use Assets;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Menu\Http\DataTables\MenuDataTable;
use Botble\Menu\Repositories\Eloquent\MenuRepository;
use Botble\Menu\Repositories\Interfaces\MenuContentInterface;
use Botble\Menu\Repositories\Interfaces\MenuNodeInterface;
use Botble\Support\Services\Cache\Cache;
use Exception;
use Menu;
use Botble\Menu\Repositories\Interfaces\MenuInterface;
use Illuminate\Http\Request;
use Botble\Menu\Http\Requests\MenuRequest;
use stdClass;

class MenuController extends BaseController
{

    /**
     * @var MenuInterface
     */
    protected $menuRepository;

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
     * MenuController constructor.
     * @param MenuInterface $menuRepository
     * @param MenuContentInterface $menuContentRepository
     * @param MenuNodeInterface $menuNodeRepository
     * @author Sang Nguyen
     */
    public function __construct(
        MenuInterface $menuRepository,
        MenuContentInterface $menuContentRepository,
        MenuNodeInterface $menuNodeRepository
    )
    {
        $this->menuRepository = $menuRepository;
        $this->menuContentRepository = $menuContentRepository;
        $this->menuNodeRepository = $menuNodeRepository;
        $this->cache = new Cache(app('cache'), MenuRepository::class);
    }

    /**
     * @param MenuDataTable $dataTable
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author Sang Nguyen
     */
    public function getList(MenuDataTable $dataTable)
    {
        page_title()->setTitle(trans('menu::menu.name'));

        return $dataTable->renderTable(['title' => trans('menu::menu.name')]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author Sang Nguyen
     */
    public function getCreate()
    {
        page_title()->setTitle(trans('menu::menu.create'));

        return view('menu::create');
    }

    /**
     * @param MenuRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @author Sang Nguyen, Tedozi Manson
     */
    public function postCreate(MenuRequest $request)
    {
        $menu = $this->menuRepository->getModel();

        $menu->name = $request->input('name');
        $menu->slug = $this->menuRepository->createSlug($request->input('name'));
        $menu = $this->menuRepository->createOrUpdate($menu);

        $menuContent = $this->menuContentRepository->getFirstBy(['menu_id' => $menu->id]);
        if (!$menuContent) {
            $menuContent = $this->menuContentRepository->getModel();
            $menuContent->menu_id = $menu->id;
            $this->menuContentRepository->createOrUpdate($menuContent);
        }

        $this->cache->flush();

        do_action(BASE_ACTION_AFTER_CREATE_CONTENT, MENU_MODULE_SCREEN_NAME, $request, $menu);

        if ($request->input('submit') === 'save') {
            return redirect()->route('menus.list')->with('success_msg', trans('bases::notices.create_success_message'));
        } else {
            return redirect()->route('menus.edit', $menu->id)->with('success_msg', trans('bases::notices.create_success_message'));
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author Sang Nguyen, Tedozi Manson
     */
    public function getEdit($id)
    {
        page_title()->setTitle(trans('menu::menu.edit'));

        Assets::addJavascript(['jquery-nestable']);
        Assets::addStylesheets(['jquery-nestable']);
        Assets::addAppModule(['menu']);

        $oldInputs = old();
        if ($oldInputs && $id == 0) {
            $oldObject = new stdClass();
            foreach ($oldInputs as $key => $row) {
                $oldObject->$key = $row;
            }
            $menu = $oldObject;
        } else {
            $menu = $this->menuRepository->findById($id);
            if (!$menu) {
                $menu = $this->menuRepository->getModel();
            }
        }

        return view('menu::edit', compact('menu'));
    }

    /**
     * @param MenuRequest $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     * @author Sang Nguyen, Tedozi Manson
     */
    public function postEdit(MenuRequest $request, $id)
    {
        $menu = $this->menuRepository->getModel()->findOrNew($id);

        $menu->name = $request->input('name');
        $this->menuRepository->createOrUpdate($menu);
        do_action(BASE_ACTION_AFTER_UPDATE_CONTENT, MENU_MODULE_SCREEN_NAME, $request, $menu);

        $menuContent = $this->menuContentRepository->getFirstBy(['menu_id' => $menu->id]);
        if (!$menuContent) {
            $menuContent = $this->menuContentRepository->getModel();
            $menuContent->menu_id = $menu->id;
            $this->menuContentRepository->createOrUpdate($menuContent);
        }


        $deletedNodes = explode(' ', ltrim($request->get('deleted_nodes', '')));
        $this->menuNodeRepository->getModel()->whereIn('id', $deletedNodes)->delete();
        Menu::recursiveSaveMenu(json_decode($request->get('menu_nodes'), true), $menuContent->id, 0);

        $this->cache->flush();

        if ($request->input('submit') === 'save') {
            return redirect()->route('menus.list')->with('success_msg', trans('bases::notices.create_success_message'));
        } else {
            return redirect()->route('menus.edit', $id)->with('success_msg', trans('bases::notices.create_success_message'));
        }
    }

    /**
     * @param $id
     * @param Request $request
     * @return array
     * @author Sang Nguyen
     */
    public function getDelete(Request $request, $id)
    {
        try {
            $menu = $this->menuRepository->findById($id);
            $related = $this->menuContentRepository->getModel()->where('menu_id', '=', $id)->pluck('id')->all();
            $this->menuContentRepository->deleteBy(['menu_id' => $id]);
            $this->menuNodeRepository->deleteBy(['menu_content_id' => $related]);
            $this->menuRepository->delete($menu);

            do_action(BASE_ACTION_AFTER_DELETE_CONTENT, MENU_MODULE_SCREEN_NAME, $request, $menu);

            return [
                'error' => false,
                'message' => trans('bases::notices.deleted'),
            ];
        } catch (Exception $ex) {
            return [
                'error' => true,
                'message' => $ex->getMessage(),
            ];
        }
    }

    /**
     * @param Request $request
     * @return array
     * @author Sang Nguyen
     */
    public function postDeleteMany(Request $request)
    {
        $ids = $request->input('ids');
        if (empty($ids)) {
            return [
                'error' => true,
                'message' => trans('bases::notices.no_select'),
            ];
        }

        foreach ($ids as $id) {
            $menu = $this->menuRepository->findById($id);
            $related = $this->menuContentRepository->getModel()->where('menu_id', '=', $id)->pluck('id')->all();
            $this->menuContentRepository->deleteBy(['menu_id' => $id]);
            $this->menuNodeRepository->deleteBy(['menu_content_id' => $related]);
            $this->menuRepository->delete($menu);
            do_action(BASE_ACTION_AFTER_DELETE_CONTENT, MENU_MODULE_SCREEN_NAME, $request, $menu);
        }

        return [
            'error' => false,
            'message' => trans('bases::notices.deleted'),
        ];
    }

    /**
     * @param Request $request
     * @return array
     * @author Sang Nguyen
     */
    public function postChangeStatus(Request $request)
    {
        $ids = $request->input('ids');
        if (empty($ids)) {
            return [
                'error' => true,
                'message' => trans('bases::notices.no_select'),
            ];
        }

        foreach ($ids as $id) {
            $menu = $this->menuRepository->findById($id);
            $menu->status = $request->input('status');
            $this->menuRepository->createOrUpdate($menu);
            do_action(BASE_ACTION_AFTER_UPDATE_CONTENT, MENU_MODULE_SCREEN_NAME, $request, $menu);
        }

        return [
            'error' => false,
            'status' => $request->input('status'),
            'message' => trans('bases::notices.update_success_message'),
        ];
    }
}
