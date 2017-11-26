<?php

namespace Botble\CustomField\Repositories\Caches;

use Botble\Support\Repositories\Caches\CacheAbstractDecorator;
use Botble\Support\Services\Cache\CacheInterface;
use Botble\CustomField\Repositories\Interfaces\FieldGroupInterface;

class FieldGroupCacheDecorator extends CacheAbstractDecorator implements FieldGroupInterface
{
    /**
     * @var FieldGroupInterface
     */
    protected $repository;

    /**
     * @var CacheInterface
     */
    protected $cache;

    /**
     * ContactCacheDecorator constructor.
     * @param FieldGroupInterface $repository
     * @param CacheInterface $cache
     * @author Sang Nguyen
     */
    public function __construct(FieldGroupInterface $repository, CacheInterface $cache)
    {
        $this->repository = $repository;
        $this->cache = $cache;
    }

    /**
     * @param int $groupId
     * @param null $parentId
     * @return mixed
     */
    public function getGroupItems($groupId, $parentId = null)
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    /**
     * @param $groupId
     * @param null|int $parentId
     * @param bool $withValue
     * @param null|string $morphClass
     * @param null|int $morphId
     * @return array
     */
    public function getFieldGroupItems($groupId, $parentId = null, $withValue = false, $morphClass = null, $morphId = null)
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }
}
