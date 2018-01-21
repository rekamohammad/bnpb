<?php

namespace Botble\Blog\Repositories\Caches;

use Botble\Support\Repositories\Caches\CacheAbstractDecorator;
use Botble\Blog\Repositories\Interfaces\KabupatenInterface;
use Botble\Support\Services\Cache\CacheInterface;

class KabupatenCacheDecorator extends CacheAbstractDecorator implements KabupatenInterface
{

    /**
     * @var AlbumInterface
     */
    protected $repository;

    /**
     * @var CacheInterface
     */
    protected $cache;

    /**
     * PostCacheDecorator constructor.
     * @param AlbumInterface $repository
     * @param CacheInterface $cache
     * @author Sang Nguyen
     */
    public function __construct(KabupatenInterface $repository, CacheInterface $cache)
    {
        $this->repository = $repository;
        $this->cache = $cache;
    }

    /**
     * @return mixed
     * @author Sang Nguyen
     */
    public function getDataSiteMap()
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    /**
     * @param $query
     * @param int $limit
     * @param int $paginate
     * @return mixed
     * @author Sang Nguyen
     */
    public function getSearch($query, $limit = 10, $paginate = 10)
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    /**
     * @param bool $active
     * @return mixed
     * @author Sang Nguyen
     */
    public function getAllPosts($active = true)
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }
}
