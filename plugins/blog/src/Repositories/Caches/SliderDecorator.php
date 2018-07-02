<?php

namespace Botble\Blog\Repositories\Caches;

use Botble\Support\Repositories\Caches\CacheAbstractDecorator;
use Botble\Blog\Repositories\Interfaces\SliderInterface;
use Botble\Support\Services\Cache\CacheInterface;

class SliderCacheDecorator extends CacheAbstractDecorator implements SliderInterface
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
    public function __construct(SliderInterface $repository, CacheInterface $cache)
    {
        $this->repository = $repository;
        $this->cache = $cache;
    }

    /**
     * @return mixed
     * @author Sang Nguyen
     */
   
    /**
     * @param bool $active
     * @return mixed
     * @author Sang Nguyen
     */
    public function getListSlider($limit)
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }
}
