<?php

namespace Botble\Media\Repositories\Eloquent;

use Botble\Media\Repositories\Interfaces\MediaShareInterface;
use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;

/**
 * Class MediaShareRepository
 * @package Botble\Media
 */
class MediaShareRepository extends RepositoriesAbstract implements MediaShareInterface
{

    /**
     * @param int $folder_id
     * @return array|\Illuminate\Database\Eloquent\Collection|static[]
     * @author Sang Nguyen
     */
    public function getSharedFiles($folder_id = 0)
    {
        return $this->model->join('media_files', 'media_files.id', '=', 'media_shares.share_id')
            ->where([
                'media_shares.share_type' => 'file',
                'media_shares.shared_by' => rv_media_get_current_user_id(),
                'media_files.folder_id' => $folder_id,
            ])
            ->select(['media_shares.share_id', 'media_files.*'])
            ->orderBy('name', 'asc')
            ->distinct()
            ->get();
    }

    /**
     * @param int $folder_id
     * @return array|\Illuminate\Database\Eloquent\Collection|static[]
     * @author Sang Nguyen
     */
    public function getSharedFolders($folder_id = 0)
    {

        return $this->model->join('media_folders', 'media_folders.id', '=', 'media_shares.share_id')
            ->where([
                'media_shares.share_type' => 'folder',
                'media_shares.shared_by' => rv_media_get_current_user_id(),
                'media_folders.parent_id' => $folder_id,
            ])
            ->select(['media_shares.share_id', 'media_folders.*'])
            ->orderBy('name', 'asc')
            ->distinct()
            ->get();
    }

    /**
     * @param int $folder_id
     * @return array|\Illuminate\Database\Eloquent\Collection|static[]
     * @author Sang Nguyen
     */
    public function getShareWithMeFiles($folder_id = 0)
    {
        return $this->model->join('media_files', 'media_files.id', '=', 'media_shares.share_id')
            ->where([
                'media_shares.share_type' => 'file',
                'media_shares.user_id' => rv_media_get_current_user_id(),
                'media_files.folder_id' => $folder_id,
            ])
            ->select(['media_shares.share_id', 'media_files.*'])
            ->orderBy('name', 'asc')
            ->distinct()
            ->get();
    }

    /**
     * @param int $folder_id
     * @return array|\Illuminate\Database\Eloquent\Collection|static[]
     * @author Sang Nguyen
     */
    public function getSharedWithMeFolders($folder_id = 0)
    {

        return $this->model->join('media_folders', 'media_folders.id', '=', 'media_shares.share_id')
            ->where([
                'media_shares.share_type' => 'folder',
                'media_shares.user_id' => rv_media_get_current_user_id(),
                'media_folders.parent_id' => $folder_id,
            ])
            ->select(['media_shares.share_id', 'media_folders.*'])
            ->orderBy('name', 'asc')
            ->distinct()
            ->get();
    }

    /**
     * @param $share_id
     * @param $share_type
     * @return mixed
     * @author Sang Nguyen
     */
    public function getSharedUsers($share_id, $share_type)
    {
        return $this->model->join('users', 'users.id', '=', 'media_shares.user_id')
            ->where([
                'shared_by' => rv_media_get_current_user_id(),
                'share_type' => $share_type,
                'share_id' => $share_id,
            ])
            ->selectRaw(config('media.user_attributes'))
            ->get();
    }
}
