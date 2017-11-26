<?php

namespace Botble\Media\Repositories\Eloquent;

use Botble\Media\Repositories\Interfaces\MediaFolderInterface;
use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;
use Request;

/**
 * Class MediaFolderRepository
 * @package Botble\Media
 * @author Sang Nguyen
 * @since 19/08/2015 07:45 AM
 */
class MediaFolderRepository extends RepositoriesAbstract implements MediaFolderInterface
{

    /**
     * @param $folder_id
     * @param array $params
     * @param bool $withTrash
     * @return mixed
     */
    public function getFolderByParentId($folder_id, array $params = [], $withTrash = false)
    {
        if (Request::input('load_more_file') == 'true') {
            return [];
        }

        $params = array_merge([
            'where' => [],
        ], $params);
        $data = $this->model
            ->where($params['where']);
        if ($folder_id != -1) {
            $data = $data->where('parent_id', '=', $folder_id);
        }

        if (config('media.mode') != 'simple') {
            if (array_get($params, 'is_public') == true) {
                $data = $data->where('is_public', '=', 1);
            } else {
                $data = $data->where('user_id', '=', rv_media_get_current_user_id());
            }
        }

        if ($withTrash) {
            $data = $data->withTrashed();
        }
        return $data->orderBy('name', 'asc')
            ->get();
    }

    /**
     * @param $name
     * @param $parent_id
     * @return mixed
     * @author Sang Nguyen
     */
    public function createSlug($name, $parent_id)
    {
        $slug = str_slug($name);
        $index = 1;
        $baseSlug = $slug;
        while ($this->checkIfExists('slug', $slug, $parent_id)) {
            $slug = $baseSlug . '-' . $index++;
        }

        return $slug;
    }

    /**
     * @param $name
     * @param $parent_id
     * @return mixed
     * @author Sang Nguyen
     */
    public function createName($name, $parent_id)
    {
        $newName = $name;
        $index = 1;
        $baseSlug = $newName;
        while ($this->checkIfExists('name', $newName, $parent_id)) {
            $newName = $baseSlug . '-' . $index++;
        }

        return $newName;
    }

    /**
     * @param $key
     * @param $value
     * @param $parent_id
     * @return mixed
     * @internal param $slug
     * @author Sang Nguyen
     */
    protected function checkIfExists($key, $value, $parent_id)
    {
        $count = $this->model->where($key, '=', $value)->where('parent_id', $parent_id)->withTrashed();
        if (config('media.mode') != 'simple') {
            $count = $count->where('user_id', '=', rv_media_get_current_user_id());
        }

        $count = $count->count();

        return $count > 0;
    }

    /**
     * @param $parent_id
     * @param array $breadcrumbs
     * @return array
     * @author Sang Nguyen
     */
    public function getBreadcrumbs($parent_id, $breadcrumbs = [])
    {
        if ($parent_id == 0) {
            return $breadcrumbs;
        }

        $folder = $this->getFirstByWithTrash(['id' => $parent_id]);

        if (empty($folder)) {
            return $breadcrumbs;
        }

        $child = $this->getBreadcrumbs($folder->parent_id, $breadcrumbs);
        return array_merge($child, [
            [
                'id' => $folder->id,
                'name' => $folder->name,
            ]
        ]);
    }

    /**
     * @param $parent_id
     * @param array $params
     * @return mixed
     * @author Sang Nguyen
     */
    public function getTrashed($parent_id, array $params = [])
    {
        $params = array_merge([
            'where' => [],
        ], $params);
        $data = $this->model
            ->where('parent_id', '=', $parent_id)
            ->where($params['where'])
            ->orderBy('name', 'asc')
            ->onlyTrashed();

        if (config('media.mode') != 'simple') {
            $data = $data->where(function ($query) {
                $query->orWhere('user_id', '=', rv_media_get_current_user_id())
                    ->orWhere('user_id', '=', 0);
            });
        }

        return $data->get();
    }

    /**
     * @param $folder_id
     * @param bool $force
     * @author Sang Nguyen
     */
    public function deleteFolder($folder_id, $force = false)
    {
        $child = $this->getFolderByParentId($folder_id, [], $force);
        foreach ($child as $item) {
            $this->deleteFolder($item->id, $force);
        }

        if ($force) {
            $this->forceDelete(['id' => $folder_id]);
        } else {
            $this->deleteBy(['id' => $folder_id]);
        }
    }

    /**
     * @param $parent_id
     * @param array $child
     * @return array
     * @internal param $folder_id
     * @author Sang Nguyen
     */
    public function getAllChildFolders($parent_id, $child = [])
    {
        if ($parent_id == 0) {
            return $child;
        }

        $folders = $this->allBy(['parent_id' => $parent_id]);

        if (!empty($folders)) {
            foreach ($folders as $folder) {
                $child[$parent_id][] = $folder;
                return $this->getAllChildFolders($folder->id, $child);
            }
        }

        return $child;
    }

    /**
     * @param $folder_id
     * @param string $path
     * @return string
     * @author Sang Nguyen
     */
    public function getFullPath($folder_id, $path = '')
    {
        if ($folder_id == 0) {
            return rv_media_get_current_user_id() . $path;
        }

        $folder = $this->getFirstByWithTrash(['id' => $folder_id]);

        if (empty($folder)) {
            return rv_media_get_current_user_id() . $path;
        }

        $child = $this->getFullPath($folder->parent_id, $path);

        return $child . '/' . $folder->slug;
    }

    /**
     * @param $folder_id
     * @author Sang Nguyen
     */
    public function restoreFolder($folder_id)
    {
        $child = $this->getFolderByParentId($folder_id, [], true);
        foreach ($child as $item) {
            $this->restoreFolder($item->id);
        }

        $this->restoreBy(['id' => $folder_id]);
    }

    /**
     * @return mixed
     * @author Sang Nguyen
     */
    public function emptyTrash()
    {
        $folders = $this->model->onlyTrashed();

        if (config('media.mode') != 'simple') {
            $folders = $folders->where('user_id', '=', rv_media_get_current_user_id());
        }
        $folders = $folders->get();
        foreach ($folders as $folder) {
            $folder->forceDelete();
        }
        return true;
    }
}
