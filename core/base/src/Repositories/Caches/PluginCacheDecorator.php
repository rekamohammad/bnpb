<?php

namespace Botble\Base\Repositories\Caches;

use Botble\Base\Repositories\Interfaces\PluginInterface;
use Botble\Support\Repositories\Caches\CacheAbstractDecorator;
use Botble\Support\Services\Cache\CacheInterface;

class PluginCacheDecorator extends CacheAbstractDecorator implements PluginInterface
{
    /**
     * @var PluginInterface
     */
    protected $repository;

    /**
     * @var CacheInterface
     */
    protected $cache;

    /**
     * PluginCacheDecorator constructor.
     * @param PluginInterface $repository
     * @param CacheInterface $cache
     * @author Sang Nguyen
     */
    public function __construct(PluginInterface $repository, CacheInterface $cache)
    {
        $this->repository = $repository;
        $this->cache = $cache;
    }
}
