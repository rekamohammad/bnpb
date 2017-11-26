<?php

namespace Botble\CustomField\Repositories\Caches;

use Botble\Support\Repositories\Caches\CacheAbstractDecorator;
use Botble\Support\Services\Cache\CacheInterface;
use Botble\CustomField\Repositories\Interfaces\CustomFieldInterface;

class CustomFieldCacheDecorator extends CacheAbstractDecorator implements CustomFieldInterface
{
    /**
     * @var CustomFieldInterface
     */
    protected $repository;

    /**
     * @var CacheInterface
     */
    protected $cache;

    /**
     * ContactCacheDecorator constructor.
     * @param CustomFieldInterface $repository
     * @param CacheInterface $cache
     * @author Sang Nguyen
     */
    public function __construct(CustomFieldInterface $repository, CacheInterface $cache)
    {
        $this->repository = $repository;
        $this->cache = $cache;
    }
}
