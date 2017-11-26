<?php

namespace Botble\ACL\Repositories\Caches;

use Botble\ACL\Repositories\Interfaces\RoleFlagInterface;
use Botble\Support\Repositories\Caches\CacheAbstractDecorator;
use Botble\Support\Services\Cache\CacheInterface;

class RoleFlagCacheDecorator extends CacheAbstractDecorator implements RoleFlagInterface
{
    /**
     * @var RoleFlagInterface
     */
    protected $repository;

    /**
     * @var CacheInterface
     */
    protected $cache;

    /**
     * UserCacheDecorator constructor.
     * @param RoleFlagInterface $repository
     * @param CacheInterface $cache
     * @author Sang Nguyen
     */
    public function __construct(RoleFlagInterface $repository, CacheInterface $cache)
    {
        $this->repository = $repository;
        $this->cache = $cache;
    }
}
