<?php

namespace Botble\Blog\Http\Controllers;

use Botble\Base\Http\Controllers\BaseController;
use Botble\Blog\Http\Requests\NasionalRequest;
use Assets;
use Botble\Blog\Models\Nasional;
use Botble\Blog\Repositories\Interfaces\NasionalInterface;
use Botble\Blog\Http\DataTables\NasionalDataTable;
use Exception;
use Illuminate\Http\Request;

class NasionalController extends BaseController
{
	// protected $linkRepository;
	protected $nasionalRepository;
	
	public function __construct(
        NasionalInterface $nasionalRepository
    )
    {
        // $this->linkRepository = $linkRepository;
        $this->nasionalRepository = $nasionalRepository;
    }
	
	public function getList(NasionalDataTable $dataTable)
	{
		page_title()->setTitle(trans('blog::nasional.list'));

        return $dataTable->renderTable(['title' => trans('blog::nasional.list'), 'icon' => 'fa fa-edit']);
	}
	
	public function getCreate()
    {
        page_title()->setTitle(trans('blog::nasional.create'));

        Assets::addJavascript(['bootstrap-tagsinput', 'typeahead', 'are-you-sure']);
        Assets::addStylesheets(['bootstrap-tagsinput']);
        Assets::addAppModule(['tags', 'slug']);

        return view('blog::nasional.create');

    }
	public function postCreate(NasionalRequest $request)
    {
        /**
         * @var Post $nasional
         */
        $nasional = New nasional;
		$nasional->categories = 'Nasional';
        $nasional->name = $request->name;
        $nasional->address = $request->address;
		$nasional->status = $request->status;
        $nasional->save();

        do_action(BASE_ACTION_AFTER_CREATE_CONTENT, POST_MODULE_SCREEN_NAME, $request, $nasional);

        if ($request->input('submit') === 'save') {
            return redirect()->route('nasional.list')->with('success_msg', trans('bases::notices.create_success_message'));
        } else {
            return redirect()->route('nasional.edit', $nasional->id)->with('success_msg', trans('bases::notices.create_success_message'));
        }
    }
	
	public function getEdit($id)
    {

        $nasional = $this->nasionalRepository->findById($id);

        if (empty($nasional)) {
            abort(404);
        }

        page_title()->setTitle(trans('blog::nasional.edit') . ' #' . $id);

        Assets::addJavascript(['bootstrap-tagsinput', 'typeahead']);
        Assets::addStylesheets(['bootstrap-tagsinput']);
        Assets::addAppModule(['tags', 'slug']);

        return view('blog::nasional.edit', compact('nasional'));
    }
	
	public function postEdit($id, NasionalRequest $request)
    {
        $nasional = $this->nasionalRepository->findById($id);
        if (empty($nasional)) {
            abort(404);
        }
        $nasional->name = $request->name;
        $nasional->address = $request->address;
		$nasional->status = $request->status;
        $nasional->save();

        do_action(BASE_ACTION_AFTER_UPDATE_CONTENT, POST_MODULE_SCREEN_NAME, $request, $nasional);

        if ($request->input('submit') === 'save') {
            return redirect()->route('nasional.list')->with('success_msg', trans('bases::notices.update_success_message'));
        } else {
            return redirect()->route('nasional.edit', $id)->with('success_msg', trans('bases::notices.update_success_message'));
        }
    }
	
	
	/**
	Delete
	*/
	
	public function getDelete(Request $request, $id)
    {
        try {
            $nasional = $this->nasionalRepository->findById($id);
            if (empty($nasional)) {
                abort(404);
            }
            $this->nasionalRepository->delete($nasional);

            do_action(BASE_ACTION_AFTER_DELETE_CONTENT, POST_MODULE_SCREEN_NAME, $request, $nasional);

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
            $nasional = $this->nasionalRepository->findById($id);
            $this->nasionalRepository->delete($nasional);
            do_action(BASE_ACTION_AFTER_DELETE_CONTENT, POST_MODULE_SCREEN_NAME, $request, $nasional);
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
            $nasional = $this->nasionalRepository->findById($id);
            $nasional->status = $request->input('status');
            $this->nasionalRepository->createOrUpdate($nasional);
            do_action(BASE_ACTION_AFTER_UPDATE_CONTENT, POST_MODULE_SCREEN_NAME, $request, $nasional);
        }

        return [
            'error' => false,
            'status' => $request->input('status'),
            'message' => trans('blog::news.notices.update_success_message'),
        ];
    }
}
