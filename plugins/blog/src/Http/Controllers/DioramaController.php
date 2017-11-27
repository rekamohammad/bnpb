<?php

namespace Botble\Blog\Http\Controllers;

use Botble\Base\Http\Controllers\BaseController;
use Botble\Blog\Http\Requests\DioramaRequest;
use Assets;
use Botble\Blog\Models\Diorama;
use Botble\Blog\Repositories\Interfaces\CategoryInterface;
// use Botble\Blog\Repositories\Interfaces\DioramaInterface;
use Botble\Blog\Repositories\Interfaces\PostInterface;
use Botble\Blog\Http\DataTables\DioramaDataTable;
use Botble\Blog\Repositories\Interfaces\TagInterface;
use Botble\Blog\Services\StoreCategoryService;
use Botble\Blog\Services\StoreTagService;
use Exception;
use Illuminate\Http\Request;

class DioramaController extends BaseController
{

    /**
     * @var DioramaInterface
     */
    // protected $dioramaRepository;
    protected $postRepository;

    /**
     * @var TagInterface
     */
    protected $tagRepository;

    /**
     * @var CategoryInterface
     */
    protected $categoryRepository;

    /**
     * @param DioramaInterface $dioramaRepository
     * @param TagInterface $tagRepository
     * @param CategoryInterface $categoryRepository
     * @author Sang Nguyen
     */
    public function __construct(
        // DioramaInterface $dioramaRepository,
        PostInterface $postRepository,
        TagInterface $tagRepository,
        CategoryInterface $categoryRepository
    )
    {
        // $this->dioramaRepository = $dioramaRepository;
        $this->postRepository = $postRepository;
        $this->tagRepository = $tagRepository;
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @param DioramaDataTable $dataTable
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author Sang Nguyen
     */
    public function getList(DioramaDataTable $dataTable)
    {
        page_title()->setTitle(trans('blog::diorama.list'));

        return $dataTable->renderTable(['title' => trans('blog::diorama.list'), 'icon' => 'fa fa-edit']);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author Sang Nguyen
     */
    public function getCreate()
    {
        page_title()->setTitle(trans('blog::diorama.create'));

        Assets::addJavascript(['bootstrap-tagsinput', 'typeahead', 'are-you-sure']);
        Assets::addStylesheets(['bootstrap-tagsinput']);
        Assets::addAppModule(['tags', 'slug']);

        $categories = get_diorama();

        return view('blog::diorama.create', compact('categories'));

    }

    /**
     * @param DioramaRequest $request
     * @param StoreTagService $tagService
     * @param StoreCategoryService $categoryService
     * @return \Illuminate\Http\RedirectResponse
     * @author Sang Nguyen
     */
    public function postCreate(DioramaRequest $request, StoreTagService $tagService, StoreCategoryService $categoryService)
    {
        /**
         * @var Post $post
         */
        $post = $this->postRepository->createOrUpdate(array_merge($request->input(), [
            'user_id' => acl_get_current_user_id(),
            'featured' => $request->input('featured', false),
        ]));

        do_action(BASE_ACTION_AFTER_CREATE_CONTENT, POST_MODULE_SCREEN_NAME, $request, $post);

        $tagService->execute($request, $post);

        $categoryService->execute($request, $post);

        if ($request->input('submit') === 'save') {
            return redirect()->route('diorama.list')->with('success_msg', trans('bases::notices.create_success_message'));
        } else {
            return redirect()->route('diorama.edit', $post->id)->with('success_msg', trans('bases::notices.create_success_message'));
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author Sang Nguyen
     */
    public function getEdit($id)
    {

        $post = $this->postRepository->findById($id);

        if (empty($post)) {
            abort(404);
        }

        page_title()->setTitle(trans('blog::diorama.edit') . ' #' . $id);

        Assets::addJavascript(['bootstrap-tagsinput', 'typeahead']);
        Assets::addStylesheets(['bootstrap-tagsinput']);
        Assets::addAppModule(['tags', 'slug']);

        $tags = $post->tags->pluck('name')->all();
        $tags = implode(',', $tags);

        $categories = get_diorama();
        $selected_categories = [$categories[0]['attributes']['id']];

        return view('blog::diorama.edit', compact('post', 'tags', 'categories', 'selected_categories'));
    }

    /**
     * @param $id
     * @param DioramaRequest $request
     * @param StoreTagService $tagService
     * @param StoreCategoryService $categoryService
     * @return \Illuminate\Http\RedirectResponse
     * @author Sang Nguyen
     */
    public function postEdit($id, DioramaRequest $request, StoreTagService $tagService, StoreCategoryService $categoryService)
    {
        $post = $this->postRepository->findById($id);
        if (empty($post)) {
            abort(404);
        }

        $post->fill($request->all());
        $post->featured = $request->input('featured', false);

        $this->postRepository->createOrUpdate($post);

        do_action(BASE_ACTION_AFTER_UPDATE_CONTENT, POST_MODULE_SCREEN_NAME, $request, $post);

        $tagService->execute($request, $post);

        $categoryService->execute($request, $post);

        if ($request->input('submit') === 'save') {
            return redirect()->route('diorama.list')->with('success_msg', trans('bases::notices.update_success_message'));
        } else {
            return redirect()->route('diorama.edit', $id)->with('success_msg', trans('bases::notices.update_success_message'));
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
            $post = $this->dioramaRepository->findById($id);
            if (empty($post)) {
                abort(404);
            }
            $this->dioramaRepository->delete($post);

            do_action(BASE_ACTION_AFTER_DELETE_CONTENT, POST_MODULE_SCREEN_NAME, $request, $post);

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
            $post = $this->dioramaRepository->findById($id);
            $this->dioramaRepository->delete($post);
            do_action(BASE_ACTION_AFTER_DELETE_CONTENT, POST_MODULE_SCREEN_NAME, $request, $post);
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
            $post = $this->dioramaRepository->findById($id);
            $post->status = $request->input('status');
            $this->dioramaRepository->createOrUpdate($post);
            do_action(BASE_ACTION_AFTER_UPDATE_CONTENT, POST_MODULE_SCREEN_NAME, $request, $post);
        }

        return [
            'error' => false,
            'status' => $request->input('status'),
            'message' => trans('blog::news.notices.update_success_message'),
        ];
    }

    /**
     * @param Request $request
     * @return mixed
     * @author Sang Nguyen
     */
    public function postCreateSlug(Request $request)
    {
        return $this->postRepository->createSlug($request->input('name'), $request->input('id'));
    }

    /**
     * @return array
     * @author Sang Nguyen
     */
    public function getWidgetRecentPosts()
    {
        $limit = request()->input('paginate', 10);
        $posts = $this->dioramaRepository->getModel()->orderBy('created_at', 'desc')->paginate($limit);
        return [
            'error' => false,
            'data' => view('blog::news.widgets.posts', compact('posts', 'limit'))->render(),
        ];
    }
}
