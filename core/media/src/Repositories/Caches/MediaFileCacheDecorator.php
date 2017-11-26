<?php

namespace Botble\Media\Repositories\Caches;

use Botble\Media\Repositories\Interfaces\MediaFileInterface;
use Botble\Support\Services\Cache\CacheInterface;
use Botble\Support\Repositories\Caches\CacheAbstractDecorator;

class MediaFileCacheDecorator extends CacheAbstractDecorator implements MediaFileInterface
{

    /**
     * @var MediaFileInterface
     */
    protected $repository;

    /**
     * @var CacheInterface
     */
    protected $cache;

    /**
     * FileCacheDecorator constructor.
     * @param MediaFileInterface $repository
     * @param CacheInterface $cache
     * @author Sang Nguyen
     */
    public function __construct(MediaFileInterface $repository, CacheInterface $cache)
    {
        $this->repository = $repository;
        $this->cache = $cache;
    }

    /**
     * @return mixed
     * @author Sang Nguyen
     */
    public function getSpaceUsed()
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    /**
     * @return mixed
     * @author Sang Nguyen
     */
    public function getSpaceLeft()
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    /**
     * @return mixed
     * @author Sang Nguyen
     */
    public function getQuota()
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    /**
     * @return mixed
     * @author Sang Nguyen
     */
    public function getPercentageUsed()
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    /**
     * @param $name
     * @param $folder
     * @return mixed
     * @author Sang Nguyen
     */
    public function createName($name, $folder)
    {
        return $this->flushCacheAndUpdateData(__FUNCTION__, func_get_args());
    }

    /**
     * @param $name
     * @param $extension
     * @param $folder
     * @return mixed
     * @author Sang Nguyen
     */
    public function createSlug($name, $extension, $folder)
    {
        return $this->flushCacheAndUpdateData(__FUNCTION__, func_get_args());
    }

    /**
     * @param $folder_id
     * @param array $params
     * @return mixed
     * @author Sang Nguyen
     */
    public function getFilesByFolderId($folder_id, array $params = [])
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    /**
     * @return mixed
     * @author Sang Nguyen
     */
    public function emptyTrash()
    {
        return $this->flushCacheAndUpdateData(__FUNCTION__, func_get_args());
    }

    /**
     * @param $folder_id
     * @param array $params
     * @return mixed
     */
    public function getTrashed($folder_id, array $params = [])
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }
}
