<?php

namespace Botble\Blog\Http\Controllers;

use Botble\Base\Http\Controllers\BaseController;
use Botble\Blog\Http\DataTables\TagDataTable;
use Botble\Blog\Http\Requests\TagRequest;
use Assets;
use Botble\Blog\Repositories\Interfaces\TagInterface;
use Exception;
use Illuminate\Http\Request;

class TagController extends BaseController
{

    /**
     * @var TagInterface
     */
    protected $tagRepository;

    /**
     * @param TagInterface $tagRepository
     */
    public function __construct(TagInterface $tagRepository)
    {
        $this->tagRepository = $tagRepository;
    }

    /**
     * @param TagDataTable $dataTable
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author Sang Nguyen
     */
    public function getList(TagDataTable $dataTable)
    {

        page_title()->setTitle(trans('blog::tags.list'));

        return $dataTable->renderTable(['title' => trans('blog::tags.list')]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author Sang Nguyen
     */
    public function getCreate()
    {
        page_title()->setTitle(trans('blog::tags.create'));

        Assets::addAppModule(['slug']);
        return view('blog::tags.create');
    }

    /**
     * @param TagRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @author Sang Nguyen
     */
    public function postCreate(TagRequest $request)
    {
        $tag = $this->tagRepository->createOrUpdate(array_merge($request->input(), ['user_id' => acl_get_current_user_id()]));
        do_action(BASE_ACTION_AFTER_CREATE_CONTENT, TAG_MODULE_SCREEN_NAME, $request, $tag);
        if ($request->input('submit') === 'save') {
            return redirect()->route('tags.list')->with('success_msg', trans('bases::notices.create_success_message'));
        } else {
            return redirect()->route('tags.edit', $tag->id)->with('success_msg', trans('bases::notices.create_success_message'));
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author Sang Nguyen
     */
    public function getEdit($id)
    {
        $tag = $this->tagRepository->findById($id);
        if (empty($tag)) {
            abort(404);
        }

        page_title()->setTitle(trans('blog::tags.edit') . ' #' . $id);

        Assets::addAppModule(['slug']);

        return view('blog::tags.edit', compact('tag'));
    }

    /**
     * @param $id
     * @param TagRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @author Sang Nguyen
     */
    public function postEdit($id, TagRequest $request)
    {
        $tag = $this->tagRepository->findById($id);
        if (empty($tag)) {
            abort(404);
        }
        $tag->fill($request->all());

        $this->tagRepository->createOrUpdate($tag);
        do_action(BASE_ACTION_AFTER_UPDATE_CONTENT, TAG_MODULE_SCREEN_NAME, $request, $tag);
        if ($request->input('submit') === 'save') {
            return redirect()->route('tags.list')->with('success_msg', trans('bases::notices.update_success_message'));
        } else {
            return redirect()->route('tags.edit', $id)->with('success_msg', trans('bases::notices.update_success_message'));
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
            $tag = $this->tagRepository->findById($id);
            if (empty($tag)) {
                abort(404);
            }
            $this->tagRepository->delete($tag);

            do_action(BASE_ACTION_AFTER_DELETE_CONTENT, TAG_MODULE_SCREEN_NAME, $request, $tag);

            return [
                'error' => false,
                'message' => trans('blog::tags.deleted'),
            ];
        } catch (Exception $e) {
            return [
                'error' => true,
                'message' => trans('blog::tags.cannot_delete'),
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
                'message' => trans('blog::tags.notices.no_select'),
            ];
        }

        foreach ($ids as $id) {
            $tag = $this->tagRepository->findById($id);
            $this->tagRepository->delete($tag);

            do_action(BASE_ACTION_AFTER_DELETE_CONTENT, TAG_MODULE_SCREEN_NAME, $request, $tag);
        }
        return [
            'error' => false,
            'status' => $request->input('status'),
            'message' => trans('blog::tags.deleted'),
        ];
    }

    /**
     * Get list tags in db
     *
     * @return mixed
     * @author Sang Nguyen
     */
    public function getAllTags()
    {
        return $this->tagRepository->pluck('name');
    }

    /**
     * @param Request $request
     * @return mixed
     * @author Sang Nguyen
     */
    public function postCreateSlug(Request $request)
    {
        return $this->tagRepository->createSlug($request->input('name'), $request->input('id'));
    }
}
