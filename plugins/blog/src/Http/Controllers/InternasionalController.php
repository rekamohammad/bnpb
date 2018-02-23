<?php

namespace Botble\Blog\Http\Controllers;

use Botble\Base\Http\Controllers\BaseController;
use Botble\Blog\Http\Requests\InternasionalRequest;
use Assets;
use Botble\Blog\Models\Internasional;
use Botble\Blog\Repositories\Interfaces\InternasionalInterface;
use Botble\Blog\Http\DataTables\InternasionalDataTable;
use Exception;
use Illuminate\Http\Request;

class InternasionalController extends BaseController
{
	// protected $linkRepository;
	protected $internasionalRepository;
	
	public function __construct(
        InternasionalInterface $internasionalRepository
    )
    {
        // $this->linkRepository = $linkRepository;
        $this->internasionalRepository = $internasionalRepository;
    }
	
	public function getList(InternasionalDataTable $dataTable)
	{
		page_title()->setTitle(trans('blog::internasional.list'));

        return $dataTable->renderTable(['title' => trans('blog::Internasional.list'), 'icon' => 'fa fa-edit']);
	}
	
	public function getCreate()
    {
        page_title()->setTitle(trans('blog::Internasional.create'));

        Assets::addJavascript(['bootstrap-tagsinput', 'typeahead', 'are-you-sure']);
        Assets::addStylesheets(['bootstrap-tagsinput']);
        Assets::addAppModule(['tags', 'slug']);

        return view('blog::internasional.create');

    }
	public function postCreate(InternasionalRequest $request)
    {
        /**
         * @var Post $nasional
         */
        $internasional = New internasional;
		$internasional->categories = 'Internasional';
        $internasional->name = $request->name;
        $internasional->url = $request->url;
		$internasional->status = $request->status;
        $internasional->save();

        do_action(BASE_ACTION_AFTER_CREATE_CONTENT, POST_MODULE_SCREEN_NAME, $request, $internasional);

        if ($request->input('submit') === 'save') {
            return redirect()->route('internasional.list')->with('success_msg', trans('bases::notices.create_success_message'));
        } else {
            return redirect()->route('internasional.edit', $internasional->id)->with('success_msg', trans('bases::notices.create_success_message'));
        }
    }
	
	public function getEdit($id)
    {

        $internasional = $this->internasionalRepository->findById($id);

        if (empty($internasional)) {
            abort(404);
        }

        page_title()->setTitle(trans('blog::internasional.edit') . ' #' . $id);

        Assets::addJavascript(['bootstrap-tagsinput', 'typeahead']);
        Assets::addStylesheets(['bootstrap-tagsinput']);
        Assets::addAppModule(['tags', 'slug']);

        return view('blog::internasional.edit', compact('internasional'));
    }
	
	public function postEdit($id, InternasionalRequest $request)
    {
        $internasional = $this->internasionalRepository->findById($id);
        if (empty($internasional)) {
            abort(404);
        }
        $internasional->categories = 'Internasional';
        $internasional->name = $request->name;
        $internasional->url = $request->url;
		$internasional->status = $request->status;
        $internasional->save();

        do_action(BASE_ACTION_AFTER_UPDATE_CONTENT, POST_MODULE_SCREEN_NAME, $request, $internasional);

        if ($request->input('submit') === 'save') {
            return redirect()->route('internasional.list')->with('success_msg', trans('bases::notices.update_success_message'));
        } else {
            return redirect()->route('internasional.edit', $id)->with('success_msg', trans('bases::notices.update_success_message'));
        }
    }
	
	
	/**
	Delete
	*/
	
	public function getDelete(Request $request, $id)
    {
        try {
            $internasional = $this->internasionalRepository->findById($id);
            if (empty($internasional)) {
                abort(404);
            }
            $this->internasionalRepository->delete($internasional);

            do_action(BASE_ACTION_AFTER_DELETE_CONTENT, POST_MODULE_SCREEN_NAME, $request, $internasional);

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
            $internasional = $this->internasionalRepository->findById($id);
            $this->internasionalRepository->delete($internasional);
            do_action(BASE_ACTION_AFTER_DELETE_CONTENT, POST_MODULE_SCREEN_NAME, $request, $internasional);
        }

        return [
            'error' => false,
            'message' => trans('bases::notices.deleted'),
        ];
    }
	
	
	
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
            $internasional = $this->internasionalRepository->findById($id);
            $internasional->status = $request->input('status');
            $this->internasionalRepository->createOrUpdate($internasional);
            do_action(BASE_ACTION_AFTER_UPDATE_CONTENT, POST_MODULE_SCREEN_NAME, $request, $internasional);
        }

        return [
            'error' => false,
            'status' => $request->input('status'),
            'message' => trans('blog::news.notices.update_success_message'),
        ];
    }
}
