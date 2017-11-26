<?php

namespace Botble\Page\Http\Controllers;

use Botble\Base\Http\Controllers\BaseController;
use Botble\Page\Http\DataTables\PageDataTable;
use Botble\Page\Http\Requests\PageRequest;
use Assets;
use Botble\Page\Repositories\Interfaces\PageInterface;
use Exception;
use Illuminate\Http\Request;

class PageController extends BaseController
{

    /**
     * @var PageInterface
     */
    protected $pageRepository;

    /**
     * PageController constructor.
     * @param PageInterface $pageRepository
     * @author Sang Nguyen
     */
    public function __construct(PageInterface $pageRepository)
    {
        $this->pageRepository = $pageRepository;
    }

    /**
     * @param PageDataTable $dataTable
     * @return \Illuminate\Http\JsonResponse|\Illuminate\View\View
     * @author Sang Nguyen
     */
    public function getList(PageDataTable $dataTable)
    {
        page_title()->setTitle(trans('pages::pages.list'));

        return $dataTable->renderTable(['title' => trans('pages::pages.list'), 'icon' => 'fa fa-book']);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author Sang Nguyen
     */
    public function getCreate()
    {
        page_title()->setTitle(trans('pages::pages.create'));

        Assets::addAppModule(['slug']);

        $templates = get_page_templates();

        return view('pages::create', compact('templates'));
    }

    /**
     * @param PageRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @author Sang Nguyen
     */
    public function postCreate(PageRequest $request)
    {
        $page = $this->pageRepository->createOrUpdate(array_merge($request->input(), [
            'user_id' => acl_get_current_user_id(),
            'featured' => $request->input('featured', false),
        ]));

        do_action(BASE_ACTION_AFTER_CREATE_CONTENT, PAGE_MODULE_SCREEN_NAME, $request, $page);

        if ($request->input('submit') === 'save') {
            return redirect()->route('pages.list')->with('success_msg', trans('bases::notices.create_success_message'));
        } else {
            return redirect()->route('pages.edit', $page->id)->with('success_msg', trans('bases::notices.create_success_message'));
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author Sang Nguyen
     */
    public function getEdit($id)
    {
        $page = $this->pageRepository->findById($id);

        if (empty($page)) {
            abort(404);
        }

        page_title()->setTitle(trans('pages::pages.edit') . ' #' . $id);

        Assets::addAppModule(['slug']);


        $templates = get_page_templates();

        return view('pages::edit', compact('page', 'templates'));
    }

    /**
     * @param $id
     * @param PageRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @author Sang Nguyen
     */
    public function postEdit($id, PageRequest $request)
    {
        $page = $this->pageRepository->findById($id);
        if (empty($page)) {
            abort(404);
        }
        $page->fill($request->input());
        $page->featured = $request->input('featured', false);

        $page = $this->pageRepository->createOrUpdate($page);

        do_action(BASE_ACTION_AFTER_UPDATE_CONTENT, PAGE_MODULE_SCREEN_NAME, $request, $page);

        if ($request->input('submit') === 'save') {
            return redirect()->route('pages.list')->with('success_msg', trans('bases::notices.update_success_message'));
        } else {
            return redirect()->route('pages.edit', $id)->with('success_msg', trans('bases::notices.update_success_message'));
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
            $page = $this->pageRepository->findById($id);
            if (empty($page)) {
                abort(404);
            }
            $this->pageRepository->delete($page);

            do_action(BASE_ACTION_AFTER_DELETE_CONTENT, PAGE_MODULE_SCREEN_NAME, $request, $page);

            return [
                'error' => false,
                'message' => trans('pages::pages.deleted'),
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
                'message' => trans('pages::pages.notices.no_select'),
            ];
        }

        foreach ($ids as $id) {
            $page = $this->pageRepository->findById($id);
            $this->pageRepository->delete($page);

            do_action(BASE_ACTION_AFTER_DELETE_CONTENT, PAGE_MODULE_SCREEN_NAME, $request, $page);
        }

        return [
            'error' => false,
            'message' => trans('pages::pages.deleted'),
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
                'message' => trans('pages::pages.notices.no_select'),
            ];
        }

        foreach ($ids as $id) {
            $page = $this->pageRepository->findById($id);
            $page->status = $request->input('status');
            $this->pageRepository->createOrUpdate($page);
            do_action(BASE_ACTION_AFTER_UPDATE_CONTENT, PAGE_MODULE_SCREEN_NAME, $request, $page);
        }

        return [
            'error' => false,
            'status' => $request->input('status'),
            'message' => trans('pages::pages.notices.update_success_message'),
        ];
    }

    /**
     * @param Request $request
     * @return mixed
     * @author Sang Nguyen
     */
    public function postCreateSlug(Request $request)
    {
        return $this->pageRepository->createSlug($request->input('name'), $request->input('id'));
    }
}
