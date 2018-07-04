<?php

namespace Botble\Blog\Http\Controllers;

use Botble\Base\Http\Controllers\BaseController;
use Botble\Blog\Http\Requests\KebencanaanRequest;
use Assets;
use Botble\Blog\Models\Kebencanaan;
use Botble\Blog\Repositories\Interfaces\KebencanaanInterface;
use Botble\Blog\Http\DataTables\KebencanaanDataTable;
use Exception;
use Illuminate\Http\Request;

class KebencanaanController extends BaseController
{

    /**
     * @var KebencanaanInterface
     */
    // protected $infografisRepository;
    protected $kebencanaanRepository;

    /**
     * @param KebencanaanInterface $infografisRepository
     * @author Sang Nguyen
     */
    public function __construct(KebencanaanInterface $kebencanaanRepository)
    {
        $this->kebencanaanRepository = $kebencanaanRepository;
    }

    /**
     * @param KebencanaanDataTable $dataTable
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author Sang Nguyen
     */
    public function getList(KebencanaanDataTable $dataTable)
    {
        page_title()->setTitle(trans('blog::kebencanaan.list'));

        return $dataTable->renderTable(['title' => trans('blog::kebencanaan.list'), 'icon' => 'fa fa-edit']);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author Sang Nguyen
     */
    public function getDefinisi()
    {
        $kebencanaan = Kebencanaan::where('type', 'definisi')->first();

        page_title()->setTitle(trans('blog::kebencanaan.create'));

        Assets::addJavascript(['bootstrap-tagsinput', 'typeahead', 'are-you-sure']);
        Assets::addStylesheets(['bootstrap-tagsinput']);

        if (empty($kebencanaan)) {
            return view('blog::kebencanaan.create');
        } else {
            return view('blog::kebencanaan.edit', compact('kebencanaan'));
        }
    }

    /**
     * @param KebencanaanRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @author Sang Nguyen
     */
    public function postDefinisi(KebencanaanRequest $request)
    {
        /**
         * @var Kebencanaan $post
         */

        $kebencanaan = Kebencanaan::where('type', 'definisi')->first();

        if (empty ($kebencanaan)) {
            $kebencanaan = New Kebencanaan;
        }
        $kebencanaan->name = $request->name;
        $kebencanaan->type = "definisi";
        $kebencanaan->image = $request->image;
        $kebencanaan->content = $request->content;
        $kebencanaan->user_id = acl_get_current_user_id();
        $kebencanaan->save();

        do_action(BASE_ACTION_AFTER_CREATE_CONTENT, POST_MODULE_SCREEN_NAME, $request, $kebencanaan);

        return redirect()->route('definisi.create')->with('success_msg', trans('bases::notices.create_success_message'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author Sang Nguyen
     */
    public function getPotensi()
    {
        $kebencanaan = Kebencanaan::where('type', 'potensi')->first();

        page_title()->setTitle(trans('blog::kebencanaan.create'));

        Assets::addJavascript(['bootstrap-tagsinput', 'typeahead', 'are-you-sure']);
        Assets::addStylesheets(['bootstrap-tagsinput']);

        if (empty($kebencanaan)) {
            return view('blog::kebencanaan.create');
        } else {
            return view('blog::kebencanaan.edit', compact('kebencanaan'));
        }
    }

    /**
     * @param KebencanaanRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @author Sang Nguyen
     */
    public function postPotensi(KebencanaanRequest $request)
    {
        /**
         * @var Kebencanaan $post
         */
        $kebencanaan = Kebencanaan::where('type', 'potensi')->first();

        if (empty ($kebencanaan)) {
            $kebencanaan = New Kebencanaan;
        }
        $kebencanaan->name = $request->name;
        $kebencanaan->type = "potensi";
        $kebencanaan->image = $request->image;
        $kebencanaan->content = $request->content;
        $kebencanaan->user_id = acl_get_current_user_id();
        $kebencanaan->save();

        do_action(BASE_ACTION_AFTER_CREATE_CONTENT, POST_MODULE_SCREEN_NAME, $request, $kebencanaan);

        return redirect()->route('potensi.create')->with('success_msg', trans('bases::notices.create_success_message'));
    }

    public function getPenanggulangan()
    {
        $kebencanaan = Kebencanaan::where('type', 'penanggulangan')->first();

        page_title()->setTitle(trans('blog::kebencanaan.create'));

        Assets::addJavascript(['bootstrap-tagsinput', 'typeahead', 'are-you-sure']);
        Assets::addStylesheets(['bootstrap-tagsinput']);

        if (empty($kebencanaan)) {
            return view('blog::kebencanaan.create');
        } else {
            return view('blog::kebencanaan.edit', compact('kebencanaan'));
        }
    }

    /**
     * @param KebencanaanRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @author Sang Nguyen
     */
    public function postPenanggulangan(KebencanaanRequest $request)
    {
        /**
         * @var Kebencanaan $post
         */
        $kebencanaan = Kebencanaan::where('type', 'penanggulangan')->first();

        if (empty ($kebencanaan)) {
            $kebencanaan = New Kebencanaan;
        }
        $kebencanaan->name = $request->name;
        $kebencanaan->type = "penanggulangan";
        $kebencanaan->image = $request->image;
        $kebencanaan->content = $request->content;
        $kebencanaan->user_id = acl_get_current_user_id();
        $kebencanaan->save();

        do_action(BASE_ACTION_AFTER_CREATE_CONTENT, POST_MODULE_SCREEN_NAME, $request, $kebencanaan);

        return redirect()->route('penanggulangan.create')->with('success_msg', trans('bases::notices.create_success_message'));
    }

    public function getAnnouncement()
    {
        $kebencanaan = Kebencanaan::where('type', 'announcement')->first();

        page_title()->setTitle(trans('blog::kebencanaan.create'));

        Assets::addJavascript(['bootstrap-tagsinput', 'typeahead', 'are-you-sure']);
        Assets::addStylesheets(['bootstrap-tagsinput']);

        if (empty($kebencanaan)) {
            return view('blog::kebencanaan.create');
        } else {
            return view('blog::kebencanaan.edit', compact('kebencanaan'));
        }
    }

    /**
     * @param KebencanaanRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @author Sang Nguyen
     */
    public function postAnnouncement(KebencanaanRequest $request)
    {
        /**
         * @var Kebencanaan $post
         */

        $kebencanaan = Kebencanaan::where('type', 'announcement')->first();

        if (empty ($kebencanaan)) {
            $kebencanaan = New Kebencanaan;
        }
        $kebencanaan->name = $request->name;
        $kebencanaan->type = "announcement";
        $kebencanaan->image = $request->image;
        $kebencanaan->content = $request->content;
        $kebencanaan->user_id = acl_get_current_user_id();
        $kebencanaan->save();

        do_action(BASE_ACTION_AFTER_CREATE_CONTENT, POST_MODULE_SCREEN_NAME, $request, $kebencanaan);

        return redirect()->route('announcement.create')->with('success_msg', trans('bases::notices.create_success_message'));
    }
}
