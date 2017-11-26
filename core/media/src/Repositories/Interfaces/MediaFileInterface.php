<?php

namespace Botble\Media\Repositories\Interfaces;

use Botble\Support\Repositories\Interfaces\RepositoryInterface;

interface MediaFileInterface extends RepositoryInterface
{
    /**
     * @return mixed
     * @author Sang Nguyen
     */
    public function getSpaceUsed();

    /**
     * @return mixed
     */
    public function getSpaceLeft();

    /**
     * @return mixed
     * @author Sang Nguyen
     */
    public function getQuota();

    /**
     * @return mixed
     * @author Sang Nguyen
     */
    public function getPercentageUsed();

    /**
     * @param $name
     * @param $folder
     * @author Sang Nguyen
     */
    public function createName($name, $folder);

    /**
     * @param $name
     * @param $extension
     * @param $folder
     * @author Sang Nguyen
     */
    public function createSlug($name, $extension, $folder);

    /**
     * @param $folder_id
     * @param array $params
     * @return mixed
     */
    public function getFilesByFolderId($folder_id, array $params = []);

    /**
     * @param $folder_id
     * @param array $params
     * @return mixed
     */
    public function getTrashed($folder_id, array $params = []);

    /**
     * @return mixed
     * @author Sang Nguyen
     */
    public function emptyTrash();
}
