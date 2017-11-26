<?php

namespace Botble\Blog\Http\Controllers;

use Botble\Base\Http\Controllers\BaseController;
use Botble\Blog\Http\DataTables\CategoryDataTable;
use Botble\Blog\Http\Requests\CategoryRequest;
use Assets;
use Botble\Blog\Repositories\Interfaces\CategoryInterface;
use Exception;
use Illuminate\Http\Request;

class CategoryController extends BaseController
{

    /**
     * @var CategoryInterface
     */
    protected $categoryRepository;

    /**
     * @param CategoryInterface $categoryRepository
     * @author Sang Nguyen
     */
    public function __construct(CategoryInterface $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * Display all categories
     * @param CategoryDataTable $dataTable
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author Sang Nguyen
     */
    public function getList(CategoryDataTable $dataTable)
    {
        page_title()->setTitle(trans('blog::categories.list'));

        return $dataTable->renderTable(['title' => trans('blog::categories.list')]);
    }

    /**
     * Show create form
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author Sang Nguyen
     */
    public function getCreate()
    {
        page_title()->setTitle(trans('blog::categories.create'));

        Assets::addAppModule(['slug']);

        $categories = $this->categoryRepository->pluck('name', 'id');
        $categories[0] = __('None');
        $categories = array_sort_recursive($categories);

        return view('blog::categories.create', compact('categories'));
    }

    /**
     * Insert new Category into database
     *
     * @param CategoryRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @author Sang Nguyen
     */
    public function postCreate(CategoryRequest $request)
    {

        $category = $this->categoryRepository->createOrUpdate(array_merge($request->input(), [
            'user_id' => acl_get_current_user_id(),
            'featured' => $request->input('featured', false),
            'is_default' => $request->input('is_default', false),
        ]));

        do_action(BASE_ACTION_AFTER_CREATE_CONTENT, CATEGORY_MODULE_SCREEN_NAME, $request, $category);

        if ($request->input('submit') === 'save') {
            return redirect()->route('categories.list')->with('success_msg', trans('bases::notices.create_success_message'));
        } else {
            return redirect()->route('categories.edit', $category->id)->with('success_msg', trans('bases::notices.create_success_message'));
        }
    }

    /**
     * Show edit form
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author Sang Nguyen
     */
    public function getEdit($id)
    {
        $category = $this->categoryRepository->findById($id);

        if (empty($category)) {
            abort(404);
        }

        page_title()->setTitle(trans('blog::categories.edit') . ' #' . $id);

        Assets::addAppModule(['slug']);

        $categories = $this->categoryRepository->pluck('name', 'id');
        $categories[0] = __('None');
        $categories = array_sort_recursive($categories);

        return view('blog::categories.edit', compact('category', 'categories'));
    }

    /**
     * @param $id
     * @param CategoryRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @author Sang Nguyen
     */
    public function postEdit($id, CategoryRequest $request)
    {
        $category = $this->categoryRepository->findById($id);
        if (empty($category)) {
            abort(404);
        }

        $category->fill($request->input());
        $category->featured = $request->input('featured', false);
        $category->is_default = $request->input('is_default', false);

        $this->categoryRepository->createOrUpdate($category);

        do_action(BASE_ACTION_AFTER_UPDATE_CONTENT, CATEGORY_MODULE_SCREEN_NAME, $request, $category);

        if ($request->input('submit') === 'save') {
            return redirect()->route('categories.list')->with('success_msg', trans('bases::notices.update_success_message'));
        }
        return redirect()->route('categories.edit', $id)->with('success_msg', trans('bases::notices.update_success_message'));
    }

    /**
     * @param $id
     * @param Request $request
     * @return array
     * @author Sang Nguyen
     */
    public function getDelete(Request $request,$id)
    {
        try {
            $category = $this->categoryRepository->findById($id);
            if (empty($category)) {
                abort(404);
            }

            if (!$category->is_default) {
                $this->categoryRepository->delete($category);
                do_action(BASE_ACTION_AFTER_DELETE_CONTENT, CATEGORY_MODULE_SCREEN_NAME, $request, $category);
            }

            return [
                'error' => false,
                'message' => trans('bases::notices.deleted'),
            ];
        } catch (Exception $ex) {
            return [
                'error' => true,
                'message' => trans('bases::notices.cannot_delete'),
            ];
        }
    }

    /**
     * @param Request $request
     * @return array|\Illuminate\Http\JsonResponse
     * @author Sang Nguyen
     */
    public function postDeleteMany(Request $request)
    {
        $ids = $request->input('ids');
        if (empty($ids)) {
            return [
                'error' => true,
                'message' => trans('bases::notices.no_select'),
            ];
        }

        foreach ($ids as $id) {
            $category = $this->categoryRepository->findById($id);
            if (!$category->is_default) {
                $this->categoryRepository->delete($category);

                do_action(BASE_ACTION_AFTER_DELETE_CONTENT, CATEGORY_MODULE_SCREEN_NAME, $request, $category);
            }
        }

        return [
            'error' => false,
            'message' => trans('bases::notices.delete_success_message'),
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
                'message' => trans('bases::notices.no_select'),
            ];
        }

        foreach ($ids as $id) {
            $category = $this->categoryRepository->findById($id);
            $category->status = $request->input('status');
            $this->categoryRepository->createOrUpdate($category);
            do_action(BASE_ACTION_AFTER_UPDATE_CONTENT, CATEGORY_MODULE_SCREEN_NAME, $request, $category);
        }

        return [
            'error' => false,
            'status' => $request->input('status'),
            'message' => trans('bases::notices.update_success_message'),
        ];
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author Sang Nguyen
     */
    public function postCreateSlug(Request $request)
    {
        return $this->categoryRepository->createSlug($request->input('name'), $request->input('id'));
    }
}
