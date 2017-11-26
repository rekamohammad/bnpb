<?php

namespace Botble\Media\Http\Controllers;

use Botble\Media\Repositories\Interfaces\MediaShareInterface;
use Botble\Media\Repositories\Interfaces\UserInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use RvMedia;

/**
 * Class MediaShareController
 * @package Botble\Media\Http\Controllers
 * @author Sang Nguyen
 */
class MediaShareController extends Controller
{
    /**
     * @var MediaShareInterface
     */
    protected $shareRepository;

    /**
     * @var UserInterface
     */
    protected $userRepository;

    /**
     * MediaShareController constructor.
     * @param MediaShareInterface $mediaShareRepository
     * @param UserInterface $userRepository
     * @author Sang Nguyen
     */
    public function __construct(MediaShareInterface $mediaShareRepository, UserInterface $userRepository)
    {
        $this->shareRepository = $mediaShareRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @author Sang Nguyen
     */
    public function getSharedUsers(Request $request)
    {
        $share_id = $request->input('share_id');
        $share_type = $request->input('is_folder') == 'false' ? 'file' : 'folder';
        $shared_users = $this->shareRepository->getSharedUsers($share_id, $share_type)->pluck('id')->all();
        $users = $this->userRepository->getListUsers();

        foreach ($users as $user) {
            $user->is_selected = 0;
            if (in_array($user->id, $shared_users)) {
                $user->is_selected = 1;
            }
        }

        return RvMedia::responseSuccess(compact('users'));
    }
}
