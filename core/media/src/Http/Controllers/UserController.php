<?php

namespace Botble\Media\Http\Controllers;

use Botble\Media\Repositories\Interfaces\UserInterface;
use Illuminate\Routing\Controller;
use RvMedia;

/**
 * Class FolderController
 * @package Botble\Media\Http\Controllers
 * @author Sang Nguyen
 */
class UserController extends Controller
{
    /**
     * @var UserInterface
     */
    protected $userRepository;

    /**
     * FolderController constructor.
     * @param UserInterface $userRepository
     * @author Sang Nguyen
     */
    public function __construct(UserInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     * @author Sang Nguyen
     */
    public function getList()
    {
        $users = $this->userRepository->getListUsers();

        return RvMedia::responseSuccess($users);
    }
}
