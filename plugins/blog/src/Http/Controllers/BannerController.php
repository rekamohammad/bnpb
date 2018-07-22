<?php

namespace Botble\Blog\Http\Controllers;

use Botble\Base\Http\Controllers\BaseController;
use Botble\Blog\Http\Requests\BannerRequest;
use Assets;
use Botble\Blog\Models\Banner;
use Botble\Blog\Repositories\Interfaces\BannerInterface;
use Botble\Blog\Http\DataTables\BannerDataTable;
use Exception;
use Illuminate\Http\Request;
use Image;

class BannerController extends BaseController
{
	// protected $linkRepository;
	protected $bannerRepository;
	
	public function __construct(
        BannerInterface $bannerRepository
    )
    {
        // $this->linkRepository = $linkRepository;
        $this->bannerRepository = $bannerRepository;
    }
	
	public function getList(BannerDataTable $dataTable)
	{
		page_title()->setTitle(trans('blog::banner.list'));

        return $dataTable->renderTable(['title' => trans('blog::banner.list'), 'icon' => 'fa fa-edit']);
	}
	
	public function getCreate()
    {
        page_title()->setTitle(trans('blog::banner.create'));

        return view('blog::banner.create');

    }
	public function postCreate(BannerRequest $request)
    {
        /**
         * @var Post $banner
         */

        if($request->hasfile('upload')) {
            $image       = $request->file('upload');
            $oriFilename = $image->getClientOriginalName();
            $oriFilename =  str_replace(" ", "-", pathinfo($oriFilename, PATHINFO_FILENAME));
            $filename    = 'banner-'.$oriFilename.'.'.$image->getClientOriginalExtension();
        
            $image_resize = Image::make($image->getRealPath()); 
            $image_resize->resize(275, 100);

            $image_resize->encode('jpg');
            $image_resize->save(public_path('/uploads/banner/' .$filename));
            
            $banner = New Banner;
            $banner->title  = $request->title;
            $banner->filename = $filename;
            $banner->url    = $request->url;
            $banner->type   = 'file';
            $banner->target = $request->target;
            $banner->save();
        } else {
            $banner = New Banner;
            $banner->title  = $request->title;
            $banner->filename = $request->link;
            $banner->url    = $request->url;
            $banner->type   = 'url';
            $banner->target = $request->target;
            $banner->save();
        }

        do_action(BASE_ACTION_AFTER_CREATE_CONTENT, POST_MODULE_SCREEN_NAME, $request, $banner);

        return redirect()->route('banner.list')->with('success_msg', trans('bases::notices.create_success_message'));
    }

    public function getEdit($id)
    {

        $banner = $this->bannerRepository->findById($id);

        if (empty($banner)) {
            abort(404);
        }

        page_title()->setTitle(trans('blog::banner.edit') . ' #' . $id);

        Assets::addJavascript(['bootstrap-tagsinput', 'typeahead']);
        Assets::addStylesheets(['bootstrap-tagsinput']);
        

        return view('blog::banner.edit')->with(compact('banner'));
    }
	
	public function postEdit($id, BannerRequest $request)
    {
        $banner = $this->bannerRepository->findById($id);
        if (empty($findBanner)) {
            abort(404);
        }
		$banner->filename 	= $request->filename;
        $banner->url  		= $request->url;
		$banner->target 	= $request->target;
        $findBanner->save();

        do_action(BASE_ACTION_AFTER_UPDATE_CONTENT, POST_MODULE_SCREEN_NAME, $request, $banner);

        return redirect()->route('banner.list')->with('success_msg', trans('bases::notices.update_success_message'));
    }

	/**
	Delete
	*/
	
	public function getDelete(Request $request, $id)
    {
        try {
            $banner = $this->bannerRepository->findById($id);
            if (empty($banner)) {
                abort(404);
            }
            $this->bannerRepository->delete($banner);

            do_action(BASE_ACTION_AFTER_DELETE_CONTENT, POST_MODULE_SCREEN_NAME, $request, $banner);

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
            $banner = $this->kabupatenRepository->findById($id);
            $this->bannerRepository->delete($banner);
            do_action(BASE_ACTION_AFTER_DELETE_CONTENT, POST_MODULE_SCREEN_NAME, $request, $banner);
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
            $banner = $this->bannerRepository->findById($id);
            $banner->status = $request->input('status');
            $this->bannerRepository->createOrUpdate($banner);
            do_action(BASE_ACTION_AFTER_UPDATE_CONTENT, POST_MODULE_SCREEN_NAME, $request, $banner);
        }

        return [
            'error' => false,
            'status' => $request->input('status'),
            'message' => trans('blog::news.notices.update_success_message'),
        ];
    }
}
