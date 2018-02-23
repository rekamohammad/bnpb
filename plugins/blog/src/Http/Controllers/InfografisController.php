<?php

namespace Botble\Blog\Http\Controllers;

use Botble\Base\Http\Controllers\BaseController;
use Botble\Blog\Http\Requests\InfografisRequest;
use Assets;
use Botble\Blog\Models\Post;
use Botble\Blog\Models\Infografis;
use Botble\Blog\Repositories\Interfaces\CategoryInterface;
// use Botble\Blog\Repositories\Interfaces\InfografisInterface;
use Botble\Blog\Repositories\Interfaces\PostInterface;
use Botble\Blog\Http\DataTables\InfografisDataTable;
use Botble\Blog\Repositories\Interfaces\TagInterface;
use Botble\Blog\Services\StoreCategoryService;
use Botble\Blog\Services\StoreTagService;
use Exception;
use Illuminate\Http\Request;

class InfografisController extends BaseController
{

    /**
     * @var InfografisInterface
     */
    // protected $infografisRepository;
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
     * @param InfografisInterface $infografisRepository
     * @param TagInterface $tagRepository
     * @param CategoryInterface $categoryRepository
     * @author Sang Nguyen
     */
    public function __construct(
        // InfografisInterface $infografisRepository,
        PostInterface $postRepository,
        TagInterface $tagRepository,
        CategoryInterface $categoryRepository
    )
    {
        // $this->infografisRepository = $infografisRepository;
        $this->postRepository = $postRepository;
        $this->tagRepository = $tagRepository;
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @param InfografisDataTable $dataTable
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author Sang Nguyen
     */
    public function getList(InfografisDataTable $dataTable)
    {
        page_title()->setTitle(trans('blog::infografis.list'));

        return $dataTable->renderTable(['title' => trans('blog::infografis.list'), 'icon' => 'fa fa-edit']);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author Sang Nguyen
     */
    public function getCreate()
    {
        page_title()->setTitle(trans('blog::infografis.create'));

        Assets::addJavascript(['bootstrap-tagsinput', 'typeahead', 'are-you-sure']);
        Assets::addStylesheets(['bootstrap-tagsinput']);
        Assets::addAppModule(['tags', 'slug']);

        $categories = get_category_by_parent_id('23');

        return view('blog::infografis.create', compact('categories'));

    }

    /**
     * @param InfografisRequest $request
     * @param StoreTagService $tagService
     * @param StoreCategoryService $categoryService
     * @return \Illuminate\Http\RedirectResponse
     * @author Sang Nguyen
     */
    public function postCreate(InfografisRequest $request, StoreTagService $tagService, StoreCategoryService $categoryService)
    {
        /**
         * @var Post $post
         */
        $post = New Post;
        $post->name = $request->name;
        $post->slug = 'infografis/detail/'.$request->slug;
        $post->image = $request->image;
        $post->content = $request->content;
        $post->description = $request->name;
        $post->user_id = acl_get_current_user_id();
        $post->featured = $request->input('featured', false);
        $post->category = $request->categories[0];
        $post->options = $request->options[0];
        $post->status = $request->status;
        $post->save();

        do_action(BASE_ACTION_AFTER_CREATE_CONTENT, POST_MODULE_SCREEN_NAME, $request, $post);

        $tagService->execute($request, $post);

        $categoryService->execute($request, $post);

        if ($request->input('submit') === 'save') {
            return redirect()->route('infografis.list')->with('success_msg', trans('bases::notices.create_success_message'));
        } else {
            return redirect()->route('infografis.edit', $post->id)->with('success_msg', trans('bases::notices.create_success_message'));
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

        page_title()->setTitle(trans('blog::infografis.edit') . ' #' . $id);

        Assets::addJavascript(['bootstrap-tagsinput', 'typeahead']);
        Assets::addStylesheets(['bootstrap-tagsinput']);
        Assets::addAppModule(['tags', 'slug']);

        $tags = $post->tags->pluck('name')->all();
        $tags = implode(',', $tags);
        $categories = get_category_by_parent_id('23');
        $selected_categories = [$categories[0]['attributes']['id']];
        $currentCategory = get_post_category_by_post_id($post->id);
        return view('blog::infografis.edit', compact('post', 'tags', 'categories', 'selected_categories', 'currentCategory'));
    }

    /**
     * @param $id
     * @param InfografisRequest $request
     * @param StoreTagService $tagService
     * @param StoreCategoryService $categoryService
     * @return \Illuminate\Http\RedirectResponse
     * @author Sang Nguyen
     */
    public function postEdit($id, InfografisRequest $request, StoreTagService $tagService, StoreCategoryService $categoryService)
    {
        $post = $this->postRepository->findById($id);
        if (empty($post)) {
            abort(404);
        }
        $post->name = $request->name;
        $post->slug = 'infografis/detail/'.$request->slug;
        $post->image = $request->image;
        $post->content = $request->content;
        $post->description = $request->name;
        $post->created_at = $request->created_at;
        $post->user_id = acl_get_current_user_id();
        $post->featured = $request->input('featured', false);
        $post->category = $request->categories[0];
        $post->options = $request->options[0];
        $post->status = $request->status;
        $post->save();

        do_action(BASE_ACTION_AFTER_UPDATE_CONTENT, POST_MODULE_SCREEN_NAME, $request, $post);

        $tagService->execute($request, $post);

        $categoryService->execute($request, $post);

        if ($request->input('submit') === 'save') {
            return redirect()->route('infografis.list')->with('success_msg', trans('bases::notices.update_success_message'));
        } else {
            return redirect()->route('infografis.edit', $id)->with('success_msg', trans('bases::notices.update_success_message'));
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
            $post = $this->infografisRepository->findById($id);
            if (empty($post)) {
                abort(404);
            }
            $this->infografisRepository->delete($post);

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
            $post = $this->infografisRepository->findById($id);
            $this->infografisRepository->delete($post);
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
            $post = $this->infografisRepository->findById($id);
            $post->status = $request->input('status');
            $this->infografisRepository->createOrUpdate($post);
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
        $posts = $this->infografisRepository->getModel()->orderBy('created_at', 'desc')->paginate($limit);
        return [
            'error' => false,
            'data' => view('blog::news.widgets.posts', compact('posts', 'limit'))->render(),
        ];
    }
}
