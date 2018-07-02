<?php

namespace Botble\Blog\Http\Controllers;

use Botble\Base\Http\Controllers\BaseController;
use Botble\Blog\Http\Requests\SliderRequest;
use Assets;
use Botble\Blog\Models\Slider;
use Botble\Blog\Repositories\Interfaces\SliderInterface;
use Botble\Blog\Http\DataTables\SliderDataTable;
use Exception;
use Illuminate\Http\Request;

class SliderController extends BaseController
{
	// protected $linkRepository;
	protected $sliderRepository;
	
	public function __construct(
        SliderInterface $sliderRepository
    )
    {
        // $this->linkRepository = $linkRepository;
        $this->sliderRepository = $sliderRepository;
    }
	
	public function getList(SliderDataTable $dataTable)
	{
		page_title()->setTitle(trans('blog::slider.list'));

        return $dataTable->renderTable(['title' => trans('blog::slider.list'), 'icon' => 'fa fa-edit']);
	}
	
	public function getCreate()
    {
        page_title()->setTitle(trans('blog::slider.create'));

        Assets::addJavascript(['bootstrap-tagsinput', 'typeahead', 'are-you-sure']);
        Assets::addStylesheets(['bootstrap-tagsinput']);
        Assets::addAppModule(['tags', 'slug']);

        return view('blog::slider.create');

    }
	public function postCreate(SliderRequest $request)
    {
        /**
         * @var Post $slider
         */
        $slider = New Slider;
		$slider->name 			= $request->name;
        $slider->images  		= $request->images;
		$slider->status 		= $request->status;
        $slider->save();

        do_action(BASE_ACTION_AFTER_CREATE_CONTENT, POST_MODULE_SCREEN_NAME, $request, $slider);

        if ($request->input('submit') === 'save') {
            return redirect()->route('slider.list')->with('success_msg', trans('bases::notices.create_success_message'));
        } else {
            return redirect()->route('slider.edit', $slider->id)->with('success_msg', trans('bases::notices.create_success_message'));
        }
    }
	
	public function getEdit($id)
    {

        $slider = $this->sliderRepository->findById($id);

        if (empty($slider)) {
            abort(404);
        }

        page_title()->setTitle(trans('blog::slider.edit') . ' #' . $id);

        Assets::addJavascript(['bootstrap-tagsinput', 'typeahead']);
        Assets::addStylesheets(['bootstrap-tagsinput']);
        

        return view('blog::slider.edit')->with(compact('slider'));
    }
	
	public function postEdit($id, SliderRequest $request)
    {
        $findSlider = $this->sliderRepository->findById($id);
        if (empty($findSlider)) {
            abort(404);
        }
		$findSlider->name 			= $request->name;
        $findSlider->images  		= $request->images;
		$findSlider->status 		= $request->status;
        $findSlider->save();

        do_action(BASE_ACTION_AFTER_UPDATE_CONTENT, POST_MODULE_SCREEN_NAME, $request, $findSlider);

        if ($request->input('submit') === 'save') {
            return redirect()->route('slider.list')->with('success_msg', trans('bases::notices.update_success_message'));
        } else {
            return redirect()->route('slider.edit', $id)->with('success_msg', trans('bases::notices.update_success_message'));
        }
    }
	
	
	/**
	Delete
	*/
	
	public function getDelete(Request $request, $id)
    {
        try {
            $slider = $this->sliderRepository->findById($id);
            if (empty($slider)) {
                abort(404);
            }
            $this->sliderRepository->delete($slider);

            do_action(BASE_ACTION_AFTER_DELETE_CONTENT, POST_MODULE_SCREEN_NAME, $request, $slider);

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
            $slider = $this->kabupatenRepository->findById($id);
            $this->sliderRepository->delete($slider);
            do_action(BASE_ACTION_AFTER_DELETE_CONTENT, POST_MODULE_SCREEN_NAME, $request, $slider);
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
            $slider = $this->sliderRepository->findById($id);
            $slider->status = $request->input('status');
            $this->sliderRepository->createOrUpdate($slider);
            do_action(BASE_ACTION_AFTER_UPDATE_CONTENT, POST_MODULE_SCREEN_NAME, $request, $slider);
        }

        return [
            'error' => false,
            'status' => $request->input('status'),
            'message' => trans('blog::news.notices.update_success_message'),
        ];
    }
}
