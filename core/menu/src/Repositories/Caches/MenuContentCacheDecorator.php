<?php

namespace Botble\Menu\Repositories\Caches;

use Botble\Support\Repositories\Caches\CacheAbstractDecorator;
use Botble\Menu\Repositories\Interfaces\MenuContentInterface;
use Botble\Support\Services\Cache\CacheInterface;

class MenuContentCacheDecorator extends CacheAbstractDecorator implements MenuContentInterface
{
    /**
     * @var MenuContentInterface
     */
    protected $repository;

    /**
     * @var CacheInterface
     */
    protected $cache;

    /**
     * MenuCacheDecorator constructor.
     * @param MenuContentInterface $repository
     * @param CacheInterface $cache
     * @author Sang Nguyen
     */
    public function __construct(MenuContentInterface $repository, CacheInterface $cache)
    {
        $this->repository = $repository;
        $this->cache = $cache;
    }
}
