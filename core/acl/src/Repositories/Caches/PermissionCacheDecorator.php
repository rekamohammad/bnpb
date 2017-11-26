<?php

namespace Botble\ACL\Repositories\Caches;

use Botble\ACL\Repositories\Interfaces\PermissionInterface;
use Botble\Support\Repositories\Caches\CacheAbstractDecorator;
use Botble\Support\Services\Cache\CacheInterface;

class PermissionCacheDecorator extends CacheAbstractDecorator implements PermissionInterface
{
    /**
     * @var PermissionInterface
     */
    protected $repository;

    /**
     * @var CacheInterface
     */
    protected $cache;

    /**
     * PermissionCacheDecorator constructor.
     * @param PermissionInterface $repository
     * @param CacheInterface $cache
     * @author Sang Nguyen
     */
    public function __construct(PermissionInterface $repository, CacheInterface $cache)
    {
        $this->repository = $repository;
        $this->cache = $cache;
    }

    /**
     * @param array $select
     * @return mixed
     * @author Sang Nguyen
     */
    public function getVisibleFeatures(array $select = ['*'])
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    /**
     * @param array $select
     * @return mixed
     * @author Sang Nguyen
     */
    public function getVisiblePermissions(array $select = ['*'])
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }
}
