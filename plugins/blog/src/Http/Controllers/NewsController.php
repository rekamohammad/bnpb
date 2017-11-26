<?php

namespace Botble\Blog\Http\Controllers;

use Botble\Base\Http\Controllers\BaseController;
use Botble\Blog\Http\Requests\NewsRequest;
use Assets;
use Botble\Blog\Models\News;
use Botble\Blog\Repositories\Interfaces\CategoryInterface;
use Botble\Blog\Repositories\Interfaces\NewsInterface;
use Botble\Blog\Repositories\Interfaces\PostInterface;
use Botble\Blog\Http\DataTables\NewsDataTable;
use Botble\Blog\Repositories\Interfaces\TagInterface;
use Botble\Blog\Services\StoreCategoryService;
use Botble\Blog\Services\StoreTagService;
use Exception;
use Illuminate\Http\Request;

class NewsController extends BaseController
{

    /**
     * @var NewsInterface
     */
    protected $newsRepository;
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
     * @param NewsInterface $newsRepository
     * @param TagInterface $tagRepository
     * @param CategoryInterface $categoryRepository
     * @author Sang Nguyen
     */
    public function __construct(
        NewsInterface $newsRepository,
        PostInterface $postRepository,
        TagInterface $tagRepository,
        CategoryInterface $categoryRepository
    )
    {
        $this->newsRepository = $newsRepository;
        $this->postRepository = $postRepository;
        $this->tagRepository = $tagRepository;
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @param NewsDataTable $dataTable
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author Sang Nguyen
     */
    public function getList(NewsDataTable $dataTable)
    {
        page_title()->setTitle(trans('blog::news.list'));

        return $dataTable->renderTable(['title' => trans('blog::news.list'), 'icon' => 'fa fa-edit']);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author Sang Nguyen
     */
    public function getCreate()
    {
        page_title()->setTitle(trans('blog::news.create'));

        Assets::addJavascript(['bootstrap-tagsinput', 'typeahead', 'are-you-sure']);
        Assets::addStylesheets(['bootstrap-tagsinput']);
        Assets::addAppModule(['tags', 'slug']);

        $categories = get_berita();

        return view('blog::news.create', compact('categories'));

    }

    /**
     * @param NewsRequest $request
     * @param StoreTagService $tagService
     * @param StoreCategoryService $categoryService
     * @return \Illuminate\Http\RedirectResponse
     * @author Sang Nguyen
     */
    public function postCreate(NewsRequest $request, StoreTagService $tagService, StoreCategoryService $categoryService)
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
            return redirect()->route('news.list')->with('success_msg', trans('bases::notices.create_success_message'));
        } else {
            return redirect()->route('news.edit', $post->id)->with('success_msg', trans('bases::notices.create_success_message'));
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

        page_title()->setTitle(trans('blog::news.edit') . ' #' . $id);

        Assets::addJavascript(['bootstrap-tagsinput', 'typeahead']);
        Assets::addStylesheets(['bootstrap-tagsinput']);
        Assets::addAppModule(['tags', 'slug']);

        $tags = $post->tags->pluck('name')->all();
        $tags = implode(',', $tags);

        $categories = get_berita();
        $selected_categories = [$categories[0]['attributes']['id']];

        return view('blog::news.edit', compact('post', 'tags', 'categories', 'selected_categories'));
    }

    /**
     * @param $id
     * @param NewsRequest $request
     * @param StoreTagService $tagService
     * @param StoreCategoryService $categoryService
     * @return \Illuminate\Http\RedirectResponse
     * @author Sang Nguyen
     */
    public function postEdit($id, NewsRequest $request, StoreTagService $tagService, StoreCategoryService $categoryService)
    {
        $post = $this->postRepository->findById($id);
        if (empty($post)) {
            abort(404);
        }

        $post->fill($request->all());
        $post->featured = $request->input('featured', false);

        $this->newsRepository->createOrUpdate($post);

        do_action(BASE_ACTION_AFTER_UPDATE_CONTENT, POST_MODULE_SCREEN_NAME, $request, $post);

        $tagService->execute($request, $post);

        $categoryService->execute($request, $post);

        if ($request->input('submit') === 'save') {
            return redirect()->route('news.list')->with('success_msg', trans('bases::notices.update_success_message'));
        } else {
            return redirect()->route('news.edit', $id)->with('success_msg', trans('bases::notices.update_success_message'));
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
            $post = $this->newsRepository->findById($id);
            if (empty($post)) {
                abort(404);
            }
            $this->newsRepository->delete($post);

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
            $post = $this->newsRepository->findById($id);
            $this->newsRepository->delete($post);
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
            $post = $this->newsRepository->findById($id);
            $post->status = $request->input('status');
            $this->newsRepository->createOrUpdate($post);
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
        $posts = $this->newsRepository->getModel()->orderBy('created_at', 'desc')->paginate($limit);
        return [
            'error' => false,
            'data' => view('blog::news.widgets.posts', compact('posts', 'limit'))->render(),
        ];
    }
}
