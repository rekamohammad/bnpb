<?php

namespace Botble\Blog\Repositories\Interfaces;

use Botble\Support\Repositories\Interfaces\RepositoryInterface;

interface MountainsInterface extends RepositoryInterface
{
	
    public function getAllMountains();
    
    public function getAllActiveMountains();
}
