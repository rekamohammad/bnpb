<?php

namespace Botble\Blog\Http\Controllers;

use Botble\Base\Http\Controllers\BaseController;
use Botble\Blog\Http\Requests\MountainsRequest;
use Assets;
use Botble\Blog\Models\Mountains;
use Botble\Blog\Repositories\Interfaces\MountainsInterface;
use Botble\Blog\Http\DataTables\MountainsDataTable;
use Exception;
use Illuminate\Http\Request;

class MountainsController extends BaseController
{

    /**
     * @var MountainsInterface
     */
    // protected $infografisRepository;
    protected $mountainsRepository;

    /**
     * @param MountainsInterface $infografisRepository
     * @author Sang Nguyen
     */
    public function __construct(
        MountainsInterface $mountainsRepository
    )
    {
        $this->mountainsRepository = $mountainsRepository;
    }

    /**
     * @param MountainsDataTable $dataTable
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author Sang Nguyen
     */
    public function getList(MountainsDataTable $dataTable)
    {
        page_title()->setTitle(trans('blog::mountains.list'));

        return $dataTable->renderTable(['title' => trans('blog::mountains.list'), 'icon' => 'fa fa-edit']);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author Sang Nguyen
     */
    public function getCreate()
    {
        page_title()->setTitle(trans('blog::mountains.create'));

        Assets::addJavascript(['bootstrap-tagsinput', 'typeahead', 'are-you-sure']);
        Assets::addStylesheets(['bootstrap-tagsinput']);

        return view('blog::mountains.create');

    }

    /**
     * @param MountainsRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @author Sang Nguyen
     */
    public function postCreate(MountainsRequest $request)
    {
        /**
         * @var Mountains $post
         */
        $mountain = New Mountains;
        $mountain->name = $request->name;
        $mountain->mountain_status = $request->mountain_status;
        $mountain->date_of_the_incident = $request->date_of_the_incident;
        $mountain->notes = $request->notes;
        $mountain->status = $request->status;
        $mountain->save();

        do_action(BASE_ACTION_AFTER_CREATE_CONTENT, POST_MODULE_SCREEN_NAME, $request, $mountain);

        if ($request->input('submit') === 'save') {
            return redirect()->route('mountains.list')->with('success_msg', trans('bases::notices.create_success_message'));
        } else {
            return redirect()->route('mountains.edit', $mountain->id)->with('success_msg', trans('bases::notices.create_success_message'));
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author Sang Nguyen
     */
    public function getEdit($id)
    {
        $mountain = $this->mountainsRepository->findById($id);

        if (empty($mountain)) {
            abort(404);
        }

        page_title()->setTitle(trans('blog::mountains.edit') . ' #' . $id);

        Assets::addJavascript(['bootstrap-tagsinput', 'typeahead']);
        Assets::addStylesheets(['bootstrap-tagsinput']);
        Assets::addAppModule(['tags', 'slug']);

        return view('blog::mountains.edit', compact('mountain'));
    }

    /**
     * @param $id
     * @param MountainsRequest $request
     * @param StoreTagService $tagService
     * @param StoreCategoryService $categoryService
     * @return \Illuminate\Http\RedirectResponse
     * @author Sang Nguyen
     */
    public function postEdit($id, MountainsRequest $request)
    {
        $mountain = $this->mountainsRepository->findById($id);
        if (empty($mountain)) {
            abort(404);
        }
        $mountain->name = $request->name;
        $mountain->mountain_status = $request->mountain_status;
        $mountain->date_of_the_incident = $request->date_of_the_incident;
        $mountain->notes = $request->notes;
        $mountain->status = $request->status;
        $mountain->save();

        do_action(BASE_ACTION_AFTER_UPDATE_CONTENT, POST_MODULE_SCREEN_NAME, $request, $mountain);

        if ($request->input('submit') === 'save') {
            return redirect()->route('mountains.list')->with('success_msg', trans('bases::notices.update_success_message'));
        } else {
            return redirect()->route('mountains.edit', $id)->with('success_msg', trans('bases::notices.update_success_message'));
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
            $mountain = $this->mountainsRepository->findById($id);
            if (empty($mountain)) {
                abort(404);
            }
            $this->mountainsRepository->delete($mountain);

            do_action(BASE_ACTION_AFTER_DELETE_CONTENT, POST_MODULE_SCREEN_NAME, $request, $mountain);

            return [
                'error' => false,
                'message' => trans('bases::notices.deleted'),
            ];
        } catch (Exception $e) {
            return [
                'error' => true,
                'message' => trans('bases::notices.cannot_delete'),
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
                'message' => trans('blog::news.notices.no_select'),
            ];
        }

        foreach ($ids as $id) {
            $mountain = $this->mountainsRepository->findById($id);
            $this->infografisRepository->delete($mountain);
            do_action(BASE_ACTION_AFTER_DELETE_CONTENT, POST_MODULE_SCREEN_NAME, $request, $mountain);
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
                'message' => trans('blog::news.notices.no_select'),
            ];
        }

        foreach ($ids as $id) {
            $mountain = $this->mountainsRepository->findById($id);
            $mountain->status = $request->input('status');
            $this->mountainsRepository->createOrUpdate($mountain);
            do_action(BASE_ACTION_AFTER_UPDATE_CONTENT, POST_MODULE_SCREEN_NAME, $request, $mountain);
        }

        return [
            'error' => false,
            'status' => $request->input('status'),
            'message' => trans('blog::news.notices.update_success_message'),
        ];
    }
}
