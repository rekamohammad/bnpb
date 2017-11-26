<?php

namespace Botble\Media\Repositories\Eloquent;

use Botble\Media\Repositories\Interfaces\UserInterface;
use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;

/**
 * Class UserRepository
 * @package Botble\Media
 * @author Sang Nguyen
 */
class UserRepository extends RepositoriesAbstract implements UserInterface
{
    /**
     * @return mixed
     * @author Sang Nguyen
     */
    public function getListUsers()
    {
        return $this->model->where('id', '!=', rv_media_get_current_user_id())->selectRaw(config('media.user_attributes'))->get();
    }
}
