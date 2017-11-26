<?php

namespace Botble\CustomField\Repositories\Caches;

use Botble\Support\Repositories\Caches\CacheAbstractDecorator;
use Botble\Support\Services\Cache\CacheInterface;
use Botble\CustomField\Repositories\Interfaces\FieldItemInterface;

class FieldItemCacheDecorator extends CacheAbstractDecorator implements FieldItemInterface
{
    /**
     * @var FieldItemInterface
     */
    protected $repository;

    /**
     * @var CacheInterface
     */
    protected $cache;

    /**
     * ContactCacheDecorator constructor.
     * @param FieldItemInterface $repository
     * @param CacheInterface $cache
     * @author Sang Nguyen
     */
    public function __construct(FieldItemInterface $repository, CacheInterface $cache)
    {
        $this->repository = $repository;
        $this->cache = $cache;
    }

    /**
     * @param int $id
     * @param int $fieldGroupId
     * @param int $parentId
     * @param string $slug
     * @return string
     */
    public function makeUniqueSlug($id, $fieldGroupId, $parentId, $slug)
    {
        return $this->getDataWithoutCache(__FUNCTION__, func_get_args());
    }
}
