<?php

namespace Botble\Blog\Repositories\Interfaces;

use Botble\Support\Repositories\Interfaces\RepositoryInterface;

interface TagPostInterface extends RepositoryInterface
{

    
    public function getRelatedTags($idTag, $limit);
	
}
