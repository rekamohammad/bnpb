<?php

namespace Botble\Media\Repositories\Interfaces;

use Botble\Support\Repositories\Interfaces\RepositoryInterface;

interface UserInterface extends RepositoryInterface
{
    /**
     * @return mixed
     * @author Sang Nguyen
     */
    public function getListUsers();
}
