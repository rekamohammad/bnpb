<?php

namespace Botble\Block\Http\Controllers;

use Assets;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Block\Http\Requests\BlockRequest;
use Botble\Block\Repositories\Interfaces\BlockInterface;
use Illuminate\Http\Request;
use MongoDB\Driver\Exception\Exception;
use Botble\Block\Http\DataTables\BlockDataTable;

class BlockController extends BaseController
{
    /**
     * @var BlockInterface
     */
    protected $blockRepository;

    /**
     * BlockController constructor.
     * @param BlockInterface $blockRepository
     * @author Sang Nguyen
     */
    public function __construct(BlockInterface $blockRepository)
    {
        $this->blockRepository = $blockRepository;
    }

    /**
     * Display all block
     * @param BlockDataTable $dataTable
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author Sang Nguyen
     */
    public function getList(BlockDataTable $dataTable)
    {
        page_title()->setTitle(trans('block::block.list'));

        return $dataTable->renderTable(['title' => trans('block::block.list')]);
    }

    /**
     * Show create form
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author Sang Nguyen
     */
    public function getCreate()
    {

        page_title()->setTitle(trans('block::block.create'));

        Assets::addJavascript(['are-you-sure']);

        return view('block::create');
    }

    /**
     * Insert new Block into database
     *
     * @param BlockRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @author Sang Nguyen
     */
    public function postCreate(BlockRequest $request)
    {
        $block = $this->blockRepository->getModel();
        $block->fill($request->input());
        $block->user_id = acl_get_current_user_id();
        $block->alias = $this->blockRepository->createSlug($request->input('alias'), null);

        $this->blockRepository->createOrUpdate($block);

        do_action(BASE_ACTION_AFTER_CREATE_CONTENT, BLOCK_MODULE_SCREEN_NAME, $request, $block);

        if ($request->input('submit') === 'save') {
            return redirect()->route('block.list')->with('success_msg', trans('bases::notices.create_success_message'));
        } else {
            return redirect()->route('block.edit', $block->id)->with('success_msg', trans('bases::notices.create_success_message'));
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
        $block = $this->blockRepository->findById($id);
        if (empty($block)) {
            abort(404);
        }
        page_title()->setTitle(trans('block::block.edit') . ' # ' . $id);

        Assets::addJavascript(['are-you-sure']);

        return view('block::edit', compact('block'));
    }

    /**
     * @param $id
     * @param BlockRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @author Sang Nguyen
     */
    public function postEdit($id, BlockRequest $request)
    {
        $block = $this->blockRepository->findById($id);
        if (empty($block)) {
            abort(404);
        }
        $block->fill($request->input());
        $block->alias = $this->blockRepository->createSlug($request->input('alias'), $id);

        $this->blockRepository->createOrUpdate($block);

        do_action(BASE_ACTION_AFTER_UPDATE_CONTENT, BLOCK_MODULE_SCREEN_NAME, $request, $block);

        if ($request->input('submit') === 'save') {
            return redirect()->route('block.list')->with('success_msg', trans('bases::notices.update_success_message'));
        } else {
            return redirect()->route('block.edit', $id)->with('success_msg', trans('bases::notices.update_success_message'));
        }
    }

    /**
     * @param $id
     * @return array
     * @author Sang Nguyen
     */
    public function getDelete($id)
    {
        try {
            $block = $this->blockRepository->findById($id);
            if (empty($block)) {
                abort(404);
            }
            $this->blockRepository->delete($block);

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
            $block = $this->blockRepository->findById($id);
            $this->blockRepository->delete($block);
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
            $block = $this->blockRepository->findById($id);
            $block->status = $request->input('status');
            $this->blockRepository->createOrUpdate($block);
        }

        return [
            'error' => false,
            'status' => $request->input('status'),
            'message' => trans('bases::notices.update_success_message'),
        ];
    }
}
