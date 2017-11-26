<?php

namespace Botble\ACL\Repositories\Caches;

use Botble\ACL\Repositories\Interfaces\FeatureInterface;
use Botble\Support\Repositories\Caches\CacheAbstractDecorator;
use Botble\Support\Services\Cache\CacheInterface;

class FeatureCacheDecorator extends CacheAbstractDecorator implements FeatureInterface
{
    /**
     * @var FeatureInterface
     */
    protected $repository;

    /**
     * @var CacheInterface
     */
    protected $cache;

    /**
     * UserCacheDecorator constructor.
     * @param FeatureInterface $repository
     * @param CacheInterface $cache
     * @author Sang Nguyen
     */
    public function __construct(FeatureInterface $repository, CacheInterface $cache)
    {
        $this->repository = $repository;
        $this->cache = $cache;
    }
}
