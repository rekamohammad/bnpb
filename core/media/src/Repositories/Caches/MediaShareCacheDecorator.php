<?php

namespace Botble\Media\Repositories\Caches;

use Botble\Media\Repositories\Interfaces\MediaShareInterface;
use Botble\Support\Repositories\Caches\CacheAbstractDecorator;
use Botble\Support\Services\Cache\CacheInterface;

class MediaShareCacheDecorator extends CacheAbstractDecorator implements MediaShareInterface
{
    /**
     * @var MediaShareInterface
     */
    protected $repository;

    /**
     * @var CacheInterface
     */
    protected $cache;

    /**
     * MediaShareCacheDecorator constructor.
     * @param MediaShareInterface $repository
     * @param CacheInterface $cache
     * @author Sang Nguyen
     */
    public function __construct(MediaShareInterface $repository, CacheInterface $cache)
    {
        $this->repository = $repository;
        $this->cache = $cache;
    }

    /**
     * @param $folder_id
     * @return mixed
     * @author Sang Nguyen
     */
    public function getSharedFiles($folder_id = 0)
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    /**
     * @param $folder_id
     * @return mixed
     * @author Sang Nguyen
     */
    public function getSharedFolders($folder_id = 0)
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    /**
     * @param int $folder_id
     * @return array|\Illuminate\Database\Eloquent\Collection|static[]
     * @author Sang Nguyen
     */
    public function getShareWithMeFiles($folder_id = 0)
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    /**
     * @param int $folder_id
     * @return array|\Illuminate\Database\Eloquent\Collection|static[]
     * @author Sang Nguyen
     */
    public function getSharedWithMeFolders($folder_id = 0)
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    /**
     * @param $share_id
     * @param $share_type
     * @return mixed
     * @author Sang Nguyen
     */
    public function getSharedUsers($share_id, $share_type)
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }
}
