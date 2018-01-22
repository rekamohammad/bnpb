<?php

namespace Botble\Blog\Http\Controllers;

use Botble\Base\Http\Controllers\BaseController;
use Botble\Blog\Http\Requests\ProvinsiRequest;
use Assets;
use Botble\Blog\Models\ProvinsiPost;
use Botble\Blog\Models\Provinsi;
use Botble\Blog\Repositories\Interfaces\ProvinsiInterface;
use Botble\Blog\Http\DataTables\ProvinsiDataTable;
use Exception;
use Illuminate\Http\Request;

class ProvinsiController extends BaseController
{
	// protected $linkRepository;
	protected $provinsiRepository;
	
	public function __construct(
        ProvinsiInterface $provinsiRepository
    )
    {
        // $this->linkRepository = $linkRepository;
        $this->provinsiRepository = $provinsiRepository;
    }
	
	public function getList(ProvinsiDataTable $dataTable)
	{
		page_title()->setTitle(trans('blog::provinsi.list'));

        return $dataTable->renderTable(['title' => trans('blog::provinsi.list'), 'icon' => 'fa fa-edit']);
	}
	
	public function getCreate()
    {
        page_title()->setTitle(trans('blog::provinsi.create'));

        Assets::addJavascript(['bootstrap-tagsinput', 'typeahead', 'are-you-sure']);
        Assets::addStylesheets(['bootstrap-tagsinput']);
        Assets::addAppModule(['tags', 'slug']);

        return view('blog::provinsi.create');

    }
	public function postCreate(ProvinsiRequest $request)
    {
        /**
         * @var Post $nasional
         */
		$pp = New provinsipost; 
		$pp->name = $request->provinsi;
		$pp->save();
		 
        $provinsi = New provinsi;
		$provinsi->categories = 'Provinsi';
        $provinsi->province = $pp->id;
		$provinsi->address = $request->address;
		$provinsi->status = $request->status;
        $provinsi->save();

        do_action(BASE_ACTION_AFTER_CREATE_CONTENT, POST_MODULE_SCREEN_NAME, $request, $provinsi);

        if ($request->input('submit') === 'save') {
            return redirect()->route('provinsi.list')->with('success_msg', trans('bases::notices.create_success_message'));
        } else {
            return redirect()->route('provinsi.edit', $provinsi->id)->with('success_msg', trans('bases::notices.create_success_message'));
        }
    }
	
	public function getEdit($id)
    {

        $provinsi = $this->provinsiRepository->findById($id);

        if (empty($provinsi)) {
            abort(404);
        }

        page_title()->setTitle(trans('blog::Provinsi.edit') . ' #' . $id);

        Assets::addJavascript(['bootstrap-tagsinput', 'typeahead']);
        Assets::addStylesheets(['bootstrap-tagsinput']);
        Assets::addAppModule(['tags', 'slug']);

        return view('blog::provinsi.edit', compact('provinsi'));
    }
	
	public function postEdit($id, ProvinsiRequest $request)
    {
        $provinsi = $this->provinsiRepository->findById($id);
        if (empty($provinsi)) {
            abort(404);
        }
        
		$provinsi->status = $request->status;
		$provinsi->address = $request->address;
        $provinsi->save();
		
		$pp = provinsipost::find($provinsi->province);
		$pp->name = $request->provinsi;
		$pp->save();

        do_action(BASE_ACTION_AFTER_UPDATE_CONTENT, POST_MODULE_SCREEN_NAME, $request, $provinsi);

        if ($request->input('submit') === 'save') {
            return redirect()->route('provinsi.list')->with('success_msg', trans('bases::notices.update_success_message'));
        } else {
            return redirect()->route('provinsi.edit', $id)->with('success_msg', trans('bases::notices.update_success_message'));
        }
    }
	
	
	/**
	Delete
	*/
	
	public function getDelete(Request $request, $id)
    {
        try {
            $provinsi = $this->provinsiRepository->findById($id);
            if (empty($provinsi)) {
                abort(404);
            }
            $this->provinsiRepository->delete($provinsi);

            do_action(BASE_ACTION_AFTER_DELETE_CONTENT, POST_MODULE_SCREEN_NAME, $request, $provinsi);

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
            $provinsi = $this->provinsiRepository->findById($id);
            $this->provinsiRepository->delete($Provinsi);
            do_action(BASE_ACTION_AFTER_DELETE_CONTENT, POST_MODULE_SCREEN_NAME, $request, $provinsi);
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
            $provinsi = $this->provinsiRepository->findById($id);
            $provinsi->status = $request->input('status');
            $this->provinsiRepository->createOrUpdate($provinsi);
            do_action(BASE_ACTION_AFTER_UPDATE_CONTENT, POST_MODULE_SCREEN_NAME, $request, $provinsi);
        }

        return [
            'error' => false,
            'status' => $request->input('status'),
            'message' => trans('blog::news.notices.update_success_message'),
        ];
    }
}
