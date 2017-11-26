<?php

namespace Botble\Gallery\Repositories\Interfaces;

use Botble\Support\Repositories\Interfaces\RepositoryInterface;

interface GalleryInterface extends RepositoryInterface
{

    /**
     * @param $name
     * @param $id
     * @author Sang Nguyen
     */
    public function createSlug($name, $id);

    /**
     * Get all galleries.
     *
     * @return mixed
     * @author Sang Nguyen
     */
    public function getAll();

    /**
     * @param $slug
     * @param $status
     * @return mixed
     * @author Sang Nguyen
     */
    public function getBySlug($slug, $status);

    /**
     * @return mixed
     * @author Sang Nguyen
     */
    public function getDataSiteMap();

    /**
     * @param $limit
     * @author Sang Nguyen
     */
    public function getFeaturedGalleries($limit);
}
