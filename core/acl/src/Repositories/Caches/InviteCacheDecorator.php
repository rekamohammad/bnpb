<?php

namespace Botble\ACL\Repositories\Caches;

use Botble\ACL\Repositories\Interfaces\InviteInterface;
use Botble\Support\Repositories\Caches\CacheAbstractDecorator;
use Botble\Support\Services\Cache\CacheInterface;

class InviteCacheDecorator extends CacheAbstractDecorator implements InviteInterface
{
    /**
     * @var InviteInterface
     */
    protected $repository;

    /**
     * @var CacheInterface
     */
    protected $cache;

    /**
     * UserCacheDecorator constructor.
     * @param InviteInterface $repository
     * @param CacheInterface $cache
     * @author Sang Nguyen
     */
    public function __construct(InviteInterface $repository, CacheInterface $cache)
    {
        $this->repository = $repository;
        $this->cache = $cache;
    }
}
