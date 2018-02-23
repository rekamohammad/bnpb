<?php

namespace Botble\Blog\Http\Controllers;

use Botble\Base\Http\Controllers\BaseController;
use Botble\Blog\Http\Requests\KabupatenRequest;
use Assets;
use Botble\Blog\Models\ProvinsiPost;
use Botble\Blog\Models\Kabupaten;
use Botble\Blog\Repositories\Interfaces\KabupatenInterface;
use Botble\Blog\Http\DataTables\KabupatenDataTable;
use Exception;
use Illuminate\Http\Request;

class KabupatenController extends BaseController
{
	// protected $linkRepository;
	protected $kabupatenRepository;
	
	public function __construct(
        KabupatenInterface $kabupatenRepository
    )
    {
        // $this->linkRepository = $linkRepository;
        $this->kabupatenRepository = $kabupatenRepository;
    }
	
	public function getList(KabupatenDataTable $dataTable)
	{
		page_title()->setTitle(trans('blog::kabupaten.list'));

        return $dataTable->renderTable(['title' => trans('blog::kabupaten.list'), 'icon' => 'fa fa-edit']);
	}
	
	public function getCreate()
    {
        page_title()->setTitle(trans('blog::kabupaten.create'));

        Assets::addJavascript(['bootstrap-tagsinput', 'typeahead', 'are-you-sure']);
        Assets::addStylesheets(['bootstrap-tagsinput']);
        Assets::addAppModule(['tags', 'slug']);
		
		$provinsi_post = provinsipost::all();

        return view('blog::kabupaten.create',compact('provinsi_post'));

    }
	public function postCreate(KabupatenRequest $request)
    {
        /**
         * @var Post $kabupaten
         */
        $kabupaten = New kabupaten;
		$kabupaten->categories 		= 'Kabupaten';
        $kabupaten->province 		= $request->provinsi;
		$kabupaten->kabupaten 		= $request->kabupaten;
        $kabupaten->address 		= $request->address;
		$kabupaten->status 			= $request->status;
        $kabupaten->save();

        do_action(BASE_ACTION_AFTER_CREATE_CONTENT, POST_MODULE_SCREEN_NAME, $request, $kabupaten);

        if ($request->input('submit') === 'save') {
            return redirect()->route('kabupaten.list')->with('success_msg', trans('bases::notices.create_success_message'));
        } else {
            return redirect()->route('kabupaten.edit', $kabupaten->id)->with('success_msg', trans('bases::notices.create_success_message'));
        }
    }
	
	public function getEdit($id)
    {

        $kabupaten = $this->kabupatenRepository->findById($id);

        if (empty($kabupaten)) {
            abort(404);
        }

        page_title()->setTitle(trans('blog::kabupaten.edit') . ' #' . $id);

        Assets::addJavascript(['bootstrap-tagsinput', 'typeahead']);
        Assets::addStylesheets(['bootstrap-tagsinput']);
        Assets::addAppModule(['tags', 'slug']);
		$provinsi_post = provinsipost::all();

        return view('blog::kabupaten.edit')->with(compact('provinsi_post'))->with(compact('kabupaten'));
    }
	
	public function postEdit($id, KabupatenRequest $request)
    {
        $kabupaten = $this->kabupatenRepository->findById($id);
        if (empty($kabupaten)) {
            abort(404);
        }
        $kabupaten->categories 		= 'Kabupaten';
        $kabupaten->province 		= $request->provinsi;
		$kabupaten->kabupaten 		= $request->kabupaten;
        $kabupaten->address 		= $request->address;
		$kabupaten->status 			= $request->status;
        $kabupaten->save();

        do_action(BASE_ACTION_AFTER_UPDATE_CONTENT, POST_MODULE_SCREEN_NAME, $request, $kabupaten);

        if ($request->input('submit') === 'save') {
            return redirect()->route('kabupaten.list')->with('success_msg', trans('bases::notices.update_success_message'));
        } else {
            return redirect()->route('kabupaten.edit', $id)->with('success_msg', trans('bases::notices.update_success_message'));
        }
    }
	
	
	/**
	Delete
	*/
	
	public function getDelete(Request $request, $id)
    {
        try {
            $kabupaten = $this->kabupatenRepository->findById($id);
            if (empty($kabupaten)) {
                abort(404);
            }
            $this->kabupatenRepository->delete($kabupaten);

            do_action(BASE_ACTION_AFTER_DELETE_CONTENT, POST_MODULE_SCREEN_NAME, $request, $kabupaten);

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
            $kabupaten = $this->kabupatenRepository->findById($id);
            $this->kabupatenRepository->delete($kabupaten);
            do_action(BASE_ACTION_AFTER_DELETE_CONTENT, POST_MODULE_SCREEN_NAME, $request, $kabupaten);
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
            $kabupaten = $this->kabupatenRepository->findById($id);
            $kabupaten->status = $request->input('status');
            $this->kabupatenRepository->createOrUpdate($kabupaten);
            do_action(BASE_ACTION_AFTER_UPDATE_CONTENT, POST_MODULE_SCREEN_NAME, $request, $kabupaten);
        }

        return [
            'error' => false,
            'status' => $request->input('status'),
            'message' => trans('blog::news.notices.update_success_message'),
        ];
    }
}
