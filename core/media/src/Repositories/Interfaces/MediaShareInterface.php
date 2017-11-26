<?php

namespace Botble\Media\Repositories\Interfaces;

use Botble\Support\Repositories\Interfaces\RepositoryInterface;

interface MediaShareInterface extends RepositoryInterface
{
    /**
     * @param $folder_id
     * @author Sang Nguyen
     */
    public function getSharedFiles($folder_id = 0);

    /**
     * @param $folder_id
     * @author Sang Nguyen
     */
    public function getSharedFolders($folder_id = 0);

    /**
     * @param int $folder_id
     * @return array|\Illuminate\Database\Eloquent\Collection|static[]
     * @author Sang Nguyen
     */
    public function getShareWithMeFiles($folder_id = 0);

    /**
     * @param int $folder_id
     * @return array|\Illuminate\Database\Eloquent\Collection|static[]
     * @author Sang Nguyen
     */
    public function getSharedWithMeFolders($folder_id = 0);

    /**
     * @param $share_id
     * @param $share_type
     * @return mixed
     * @author Sang Nguyen
     */
    public function getSharedUsers($share_id, $share_type);
}
