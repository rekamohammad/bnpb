<?php

namespace Botble\Blog\Http\Controllers;

use Botble\Base\Http\Controllers\BaseController;
use Botble\Blog\Http\Requests\AlbumRequest;
use Assets;
use Botble\Blog\Models\Album;
use Botble\Blog\Repositories\Interfaces\AlbumInterface;
use Botble\Blog\Http\DataTables\AlbumDataTable;
use Exception;
use Illuminate\Http\Request;

class AlbumController extends BaseController
{

    /**
     * @var AlbumInterface
     */
    // protected $albumRepository;
    protected $albumRepository;

    /**
     * @param AlbumInterface $albumRepository
     * @author Sang Nguyen
     */

    public function __construct(
        AlbumInterface $albumRepository
    )
    {
        // $this->dioramaRepository = $dioramaRepository;
        $this->albumRepository = $albumRepository;
    }

    /**
     * @param AlbumDataTable $dataTable
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author Sang Nguyen
     */
    public function getList(AlbumDataTable $dataTable)
    {
        
        page_title()->setTitle(trans('blog::album.list'));

        return $dataTable->renderTable(['title' => trans('blog::album.list'), 'icon' => 'fa fa-edit']);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author Sang Nguyen
     */
    public function getCreate()
    {
        page_title()->setTitle(trans('blog::album.create'));

        Assets::addJavascript(['bootstrap-tagsinput', 'typeahead', 'are-you-sure']);
        Assets::addStylesheets(['bootstrap-tagsinput']);
        Assets::addAppModule(['tags', 'slug']);

        return view('blog::album.create');

    }

    /**
     * @param AlbumRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @author Sang Nguyen
     */
    public function postCreate(AlbumRequest $request)
    {
        /**
         * @var Post $album
         */
        $album = New Album;
        $album->name = $request->name;
        $album->slug = strtolower(str_replace(" ", "-", trim($request->name," ")));
        $album->image = $request->image;
        $album->save();

        do_action(BASE_ACTION_AFTER_CREATE_CONTENT, POST_MODULE_SCREEN_NAME, $request, $album);

        if ($request->input('submit') === 'save') {
            return redirect()->route('album.list')->with('success_msg', trans('bases::notices.create_success_message'));
        } else {
            return redirect()->route('album.edit', $album->id)->with('success_msg', trans('bases::notices.create_success_message'));
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author Sang Nguyen
     */
    public function getEdit($id)
    {

        $album = $this->albumRepository->findById($id);

        if (empty($album)) {
            abort(404);
        }

        page_title()->setTitle(trans('blog::album.edit') . ' #' . $id);

        Assets::addJavascript(['bootstrap-tagsinput', 'typeahead']);
        Assets::addStylesheets(['bootstrap-tagsinput']);
        Assets::addAppModule(['tags', 'slug']);

        return view('blog::album.edit', compact('album'));
    }

    /**
     * @param $id
     * @param AlbumRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @author Sang Nguyen
     */
    public function postEdit($id, AlbumRequest $request)
    {
        $album = $this->albumRepository->findById($id);
        if (empty($album)) {
            abort(404);
        }
        $album->name = $request->name;
        $album->slug = strtolower(str_replace(" ", "-", trim($request->name," ")));
        $album->image = $request->image;
        $album->save();

        do_action(BASE_ACTION_AFTER_UPDATE_CONTENT, POST_MODULE_SCREEN_NAME, $request, $album);

        if ($request->input('submit') === 'save') {
            return redirect()->route('album.list')->with('success_msg', trans('bases::notices.update_success_message'));
        } else {
            return redirect()->route('album.edit', $id)->with('success_msg', trans('bases::notices.update_success_message'));
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
            $album = $this->albumRepository->findById($id);
            if (empty($album)) {
                abort(404);
            }
            $this->albumRepository->delete($album);

            do_action(BASE_ACTION_AFTER_DELETE_CONTENT, POST_MODULE_SCREEN_NAME, $request, $album);

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
            $album = $this->albumRepository->findById($id);
            $this->albumRepository->delete($album);
            do_action(BASE_ACTION_AFTER_DELETE_CONTENT, POST_MODULE_SCREEN_NAME, $request, $album);
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
            $album = $this->albumRepository->findById($id);
            $album->status = $request->input('status');
            $this->albumRepository->createOrUpdate($album);
            do_action(BASE_ACTION_AFTER_UPDATE_CONTENT, POST_MODULE_SCREEN_NAME, $request, $album);
        }

        return [
            'error' => false,
            'status' => $request->input('status'),
            'message' => trans('blog::news.notices.update_success_message'),
        ];
    }

}
