<?php

use Botble\Base\Supports\SortItemsWithChildrenHelper;
use Botble\Blog\Repositories\Interfaces\CategoryInterface;
use Botble\Blog\Repositories\Interfaces\PostInterface;
use Botble\Blog\Repositories\Interfaces\AlbumInterface;
use Botble\Blog\Repositories\Interfaces\DioramaInterface;
use Botble\Blog\Repositories\Interfaces\TagInterface;
use Botble\Blog\Repositories\Interfaces\TagPostInterface;
use Botble\Blog\Repositories\Interfaces\NasionalInterface;
use Botble\Blog\Repositories\Interfaces\InternasionalInterface;
use Botble\Blog\Repositories\Interfaces\ProvinsiInterface;
use Botble\Blog\Repositories\Interfaces\KabupatenInterface;
use Botble\Blog\Repositories\Interfaces\MountainsInterface;
use Botble\Blog\Repositories\Interfaces\SliderInterface;
use Botble\Blog\Repositories\Interfaces\KebencanaanInterface;
use Botble\Blog\Repositories\Interfaces\BannerInterface;
use Botble\Blog\Supports\PostFormat;

if (!function_exists('get_featured_posts')) {
    /**
     * @param $limit
     * @return mixed
     * @author Sang Nguyen
     */
    function get_featured_posts($limit)
    {
        return app(PostInterface::class)->getFeatured($limit);
    }
}

if (!function_exists('get_latest_posts')) {
    /**
     * @param $limit
     * @param array $excepts
     * @return mixed
     * @author Sang Nguyen
     */
    function get_latest_posts($limit, $excepts = [])
    {
        return app(PostInterface::class)->getListPostNonInList($excepts, $limit);
    }
}


if (!function_exists('get_related_posts')) {
    /**
     * @param $current_slug
     * @param $limit
     * @return mixed
     * @author Sang Nguyen
     */
    function get_related_posts($current_slug, $limit, $views = 0)
    {
        return app(PostInterface::class)->getRelated($current_slug, $limit, $views);
    }
}

if (!function_exists('get_posts_by_category')) {
    /**
     * @param $category_id
     * @param $paginate
     * @param $limit
     * @return mixed
     * @author Sang Nguyen
     */
    function get_posts_by_category($category_id, $paginate = 12, $limit = 0)
    {
        return app(PostInterface::class)->getByCategory($category_id, $paginate, $limit);
    }
}

if (!function_exists('get_posts_by_category_slide')) {
    /**
     * @param $category_id
     * @param $paginate
     * @param $limit
     * @return mixed
     * @author Sang Nguyen
     */
    function get_posts_by_category_slide($category_id, $paginate = 12, $limit = 0)
    {
        return app(PostInterface::class)->getByCategoryFeatured($category_id, $paginate, $limit);
    }
}

if (!function_exists('get_List_Slider')) {
    /**
     * @param $limit
     * @param array $excepts
     * @return mixed
     * @author Sang Nguyen
     */
    function get_List_Slider($limit)
    {
        return app(SliderInterface::class)->getListSlider($limit);
    }
}

if (!function_exists('get_List_Banner')) {
    /**
     * @param integer $limit
     * @param array $args
     * @return mixed
     * @author Sang Nguyen
     */
    function get_List_Banner()
    {
        
        return app(BannerInterface::class)->getListBanner(30);
    }
}

if (!function_exists('get_posts_by_ids')) {
    /**
     * @param $post_ids
     * @param $paginate
     * @param $limit
     * @return mixed
     * @author Sang Nguyen
     */
    function get_posts_by_ids($post_ids, $paginate = 12, $limit = 0)
    {
        return app(PostInterface::class)->getByIds($post_ids, $paginate, $limit);
    }
}

if (!function_exists('get_posts_by_tag')) {
    /**
     * @param $slug
     * @param $paginate
     * @return mixed
     * @author Sang Nguyen
     */
    function get_posts_by_tag($slug, $paginate = 12)
    {
        return app(PostInterface::class)->getByTag($slug, $paginate);
    }
}

if (!function_exists('get_posts_by_user')) {
    /**
     * @param $user_id
     * @param $paginate
     * @return mixed
     * @author Sang Nguyen
     */
    function get_posts_by_user($user_id, $paginate = 12)
    {
        return app(PostInterface::class)->getByUserId($user_id, $paginate);
    }
}

if (!function_exists('get_post_by_slug')) {
    /**
     * @param $slug
     * @return mixed
     * @author Sang Nguyen
     */
    function get_post_by_slug($slug)
    {
        return app(PostInterface::class)->getBySlug($slug, true);
    }
}


if (!function_exists('get_all_posts')) {
    /**
     * @param boolean $active
     * @return mixed
     * @author Sang Nguyen
     */
    function get_all_posts($active = true)
    {
        return app(PostInterface::class)->getAllPosts($active);
    }
}

if (!function_exists('get_recent_posts')) {
    /**
     * @param $limit
     * @return mixed
     * @author Sang Nguyen
     */
    function get_recent_posts($limit)
    {
        return app(PostInterface::class)->getRecentPosts($limit);
    }
}

if (!function_exists('get_related_tags')) {
    /**
     * @param integer $limit
     * @return mixed
     * @author Sang Nguyen
     */
    function get_related_tags($idTag, $limit = 10)
    {
        return app(TagPostInterface::class)->getRelatedTags($idTag, $limit);
    }
}


if (!function_exists('get_featured_categories')) {
    /**
     * @param $limit
     * @return mixed
     * @author Sang Nguyen
     */
    function get_featured_categories($limit)
    {
        return app(CategoryInterface::class)->getFeaturedCategories($limit);
    }
}

if (!function_exists('get_category_by_slug')) {
    /**
     * @param $slug
     * @return mixed
     * @author Sang Nguyen
     */
    function get_category_by_slug($slug)
    {
        return app(CategoryInterface::class)->getBySlug($slug, true);
    }
}

if (!function_exists('get_all_categories')) {
    /**
     * @param array $condition
     * @return mixed
     * @author Sang Nguyen
     */
    function get_all_categories(array $condition = [])
    {
        return app(CategoryInterface::class)->getAllCategories($condition);
    }
}

if (!function_exists('get_tag_by_slug')) {
    /**
     * @param $slug
     * @return mixed
     * @author Sang Nguyen
     */
    function get_tag_by_slug($slug)
    {
        return app(TagInterface::class)->getBySlug($slug, true);
    }
}

if (!function_exists('get_all_tags')) {
    /**
     * @param boolean $active
     * @return mixed
     * @author Sang Nguyen
     */
    function get_all_tags($active = true)
    {
        return app(TagInterface::class)->getAllTags($active);
    }
}

if (!function_exists('get_popular_tags')) {
    /**
     * @param integer $limit
     * @return mixed
     * @author Sang Nguyen
     */
    function get_popular_tags($limit = 10)
    {
        return app(TagInterface::class)->getPopularTags($limit);
    }
}

if (!function_exists('get_popular_posts')) {
    /**
     * @param integer $limit
     * @param array $args
     * @return mixed
     * @author Sang Nguyen
     */
    function get_popular_posts($limit = 10, array $args = [])
    {
        return app(PostInterface::class)->getPopularPosts($limit, $args);
    }
}

if (!function_exists('get_info_bencana_post')) {
    /**
     * @param integer $limit
     * @param array $args
     * @return mixed
     * @author Sang Nguyen
     */
    function get_info_bencana_post()
    {
        return app(PostInterface::class)->getInfoBencanaPost();
    }
}

if (!function_exists('get_siaga_bencana_post')) {
    /**
     * @param integer $limit
     * @param array $args
     * @return mixed
     * @author Sang Nguyen
     */
    function get_siaga_bencana_post()
    {
        return app(PostInterface::class)->getSiagaBencanaPost();
    }
}

if (!function_exists('get_diorama_slide')) {
    /**
     * @param integer $limit
     * @param array $args
     * @return mixed
     * @author Sang Nguyen
     */
    function get_diorama_slide()
    {
        return app(DioramaInterface::class)->getDioramaSlide();
    }
}

if (!function_exists('get_diorama_by_album')) {
    /**
     * @param integer $limit
     * @param array $args
     * @return mixed
     * @author Sang Nguyen
     */
    function get_diorama_by_album($albumId)
    {
        return app(DioramaInterface::class)->getDioramaByAlbum($albumId);
    }
}

if (!function_exists('get_list_nasional')) {
    /**
     * @param integer $limit
     * @param array $args
     * @return mixed
     * @author Sang Nguyen
     */
    function get_list_nasional()
    {
        return app(NasionalInterface::class)->getListLinks();
    }
}

if (!function_exists('get_all_nasional')) {
    /**
     * @param integer $limit
     * @param array $args
     * @return mixed
     * @author Sang Nguyen
     */
    function get_all_nasional()
    {
        return app(NasionalInterface::class)->getAllNasional();
    }
}

if (!function_exists('get_all_internasional')) {
    /**
     * @param integer $limit
     * @param array $args
     * @return mixed
     * @author Sang Nguyen
     */
    function get_all_internasional()
    {
        return app(InternasionalInterface::class)->getAllInternasional();
    }
}

if (!function_exists('get_all_provinsi')) {
    /**
     * @param integer $limit
     * @param array $args
     * @return mixed
     * @author Sang Nguyen
     */
    function get_all_provinsi()
    {
        return app(ProvinsiInterface::class)->getAllProvinsi();
    }
}

if (!function_exists('get_all_mountains')) {
    /**
     * @param integer $limit
     * @param array $args
     * @return mixed
     * @author Sang Nguyen
     */
    function get_all_mountains()
    {
        setlocale(LC_TIME, 'Indonesian');
        
        return app(MountainsInterface::class)->getAllActiveMountains();
    }
}

if (!function_exists('get_all_mountains_awas')) {
    /**
     * @param integer $limit
     * @param array $args
     * @return mixed
     * @author Sang Nguyen
     */
    function get_all_mountains_awas()
    {
        setlocale(LC_TIME, 'Indonesian');
        
        return app(MountainsInterface::class)->getMountainsAwas();
    }
}

if (!function_exists('get_all_mountains_siaga')) {
    /**
     * @param integer $limit
     * @param array $args
     * @return mixed
     * @author Sang Nguyen
     */
    function get_all_mountains_siaga()
    {
        setlocale(LC_TIME, 'Indonesian');
        
        return app(MountainsInterface::class)->getMountainsSiaga();
    }
}

if (!function_exists('get_all_mountains_waspada')) {
    /**
     * @param integer $limit
     * @param array $args
     * @return mixed
     * @author Sang Nguyen
     */
    function get_all_mountains_waspada()
    {
        setlocale(LC_TIME, 'Indonesian');
        
        return app(MountainsInterface::class)->getMountainsWaspada();
    }
}

if (!function_exists('get_definisi_bencana')) {
    /**
     * @param integer $limit
     * @param array $args
     * @return mixed
     * @author Sang Nguyen
     */
    function get_definisi_bencana()
    {
        setlocale(LC_TIME, 'Indonesian');
        
        return app(KebencanaanInterface::class)->getDefinisiBencana();
    }
}

if (!function_exists('get_potensi_bencana')) {
    /**
     * @param integer $limit
     * @param array $args
     * @return mixed
     * @author Sang Nguyen
     */
    function get_potensi_bencana()
    {
        setlocale(LC_TIME, 'Indonesian');
        
        return app(KebencanaanInterface::class)->getPotensiBencana();
    }
}

if (!function_exists('get_penanggulangan_bencana')) {
    /**
     * @param integer $limit
     * @param array $args
     * @return mixed
     * @author Sang Nguyen
     */
    function get_penanggulangan_bencana()
    {
        setlocale(LC_TIME, 'Indonesian');
        
        return app(KebencanaanInterface::class)->getPenanggulanganBencana();
    }
}

if (!function_exists('get_announcement_bencana')) {
    /**
     * @param integer $limit
     * @param array $args
     * @return mixed
     * @author Sang Nguyen
     */
    function get_announcement_bencana()
    {
        setlocale(LC_TIME, 'Indonesian');
        
        return app(KebencanaanInterface::class)->getAnnouncementBencana();
    }
}

if (!function_exists('get_all_kabupaten')) {
    /**
     * @param integer $limit
     * @param array $args
     * @return mixed
     * @author Sang Nguyen
     */
    function get_all_kabupaten()
    {
        return app(KabupatenInterface::class)->getAllKabupaten();
    }
}

if (!function_exists('get_all_kab_detail')) {
    /**
     * @param integer $limit
     * @param array $args
     * @return mixed
     * @author Sang Nguyen
     */
    function get_all_kab_detail($province)
    {
        return app(KabupatenInterface::class)->getAllKabDetail($province);
    }
}

if (!function_exists('get_list_album')) {
    /**
     * @param integer $limit
     * @param array $args
     * @return mixed
     * @author Sang Nguyen
     */
    function get_list_album()
    {
        return app(AlbumInterface::class)->getListAlbum();
    }
}

if (!function_exists('get_diorama_posts')) {
    /**
     * @param integer $limit
     * @param array $args
     * @return mixed
     * @author Sang Nguyen
     */
    function get_diorama_posts($limit = 10, array $args = [])
    {
        return app(PostInterface::class)->getPopularPosts($limit, $args);
    }
}

if (!function_exists('get_publikasi_posts')) {
    /**
     * @param integer $limit
     * @param array $args
     * @return mixed
     * @author Sang Nguyen
     */
    function get_publikasi_posts($limit = 10, array $args = [])
    {
        return app(PostInterface::class)->getPopularPosts($limit, $args);
    }
}

if (!function_exists('get_infografis_posts')) {
    /**
     * @param integer $limit
     * @param array $args
     * @return mixed
     * @author Sang Nguyen
     */
    function get_infografis_posts($limit = 10, array $args = [])
    {
        return app(PostInterface::class)->getPopularPosts($limit, $args);
    }
}

if (!function_exists('get_category_by_id')) {
    /**
     * @param integer $id
     * @return mixed
     * @author Sang Nguyen
     */
    function get_category_by_id($id)
    {
        return app(CategoryInterface::class)->getCategoryById($id);
    }
}

if (!function_exists('get_category_by_parent_id')) {
    /**
     * @param integer $id
     * @return mixed
     * @author Sang Nguyen
     */
    function get_category_by_parent_id($id)
    {
        return app(CategoryInterface::class)->getCategoryByParentId($id);
    }
}

if (!function_exists('get_post_category_by_post_id')) {
    /**
     * @param integer $id
     * @return mixed
     * @author Sang Nguyen
     */
    function get_post_category_by_post_id($id)
    {
        return app(CategoryInterface::class)->getPostCategoryByPostId($id);
    }
}

if (!function_exists('get_categories')) {
    /**
     * @param array $args
     * @return array|mixed
     */
    function get_categories(array $args = [])
    {
        $indent = array_get($args, 'indent', '——');

        $repo = app(CategoryInterface::class);
        $categories = $repo->getCategories(array_get($args, 'select', ['*']), [
            'categories.order' => 'ASC',
            'categories.created_at' => 'DESC'
        ]);
        $categories = sort_item_with_children($categories);
        foreach ($categories as $category) {
            $indentText = '';
            $depth = (int)$category->depth;
            for ($i = 0; $i < $depth; $i++) {
                $indentText .= $indent;
            }
            $category->indent_text = $indentText;
        }
        return $categories;
    }
}

if (!function_exists('get_categories_with_children')) {
    /**
     * @return array
     */
    function get_categories_with_children()
    {
        $repo = app(CategoryInterface::class);
        $categories = $repo->allBy(['status' => 1], [], ['id', 'name', 'parent_id']);
        $sortHelper = app(SortItemsWithChildrenHelper::class);
        $sortHelper
            ->setChildrenProperty('child_cats')
            ->setItems($categories);
        return $sortHelper->sort();
    }
}

if (!function_exists('get_berita')) {
    /**
     * @return array
     */
    function get_berita()
    {
        $repo = app(CategoryInterface::class);
        $categories = $repo->allBy(['status' => 1, 'slug' => 'berita'], [], ['id', 'name', 'parent_id']);
        $sortHelper = app(SortItemsWithChildrenHelper::class);
        $sortHelper
            ->setChildrenProperty('child_cats')
            ->setItems($categories);
        return $sortHelper->sort();
    }
}

if (!function_exists('get_album')) {
    /**
     * @return array
     */
    function get_album()
    {
        $repo = app(AlbumInterface::class);
        $categories = $repo->allBy(['status' => 1], [], ['id', 'name', 'slug', 'image']);
        return $categories;
    }
}

if (!function_exists('get_diorama')) {
    /**
     * @return array
     */
    function get_diorama()
    {
        $repo = app(CategoryInterface::class);
        $categories = $repo->allBy(['status' => 1, 'slug' => 'diorama'], [], ['id', 'name', 'parent_id']);
        $sortHelper = app(SortItemsWithChildrenHelper::class);
        $sortHelper
            ->setChildrenProperty('child_cats')
            ->setItems($categories);
        return $sortHelper->sort();
    }
}

if (!function_exists('get_diorama_newest')) {
    /**
     * @return array
     */
    function get_diorama_newest()
    {
        $repo = app(CategoryInterface::class);
        $categories = $repo->allBy(['status' => 1, 'slug' => 'diorama'], [], ['id', 'name', 'parent_id']);
        $sortHelper = app(SortItemsWithChildrenHelper::class);
        $sortHelper
            ->setChildrenProperty('child_cats')
            ->setItems($categories);
        return $sortHelper->sort();
    }
}

if (!function_exists('get_publikasi')) {
    /**
     * @return array
     */
    function get_publikasi()
    {
        $repo = app(CategoryInterface::class);
        $categories = $repo->allBy(['status' => 1, 'slug' => 'publikasi'], [], ['id', 'name', 'parent_id']);
        $sortHelper = app(SortItemsWithChildrenHelper::class);
        $sortHelper
            ->setChildrenProperty('child_cats')
            ->setItems($categories);
        return $sortHelper->sort();
    }
}

if (!function_exists('get_infografis')) {
    /**
     * @return array
     */
    function get_infografis($slug)
    {
        $repo = app(CategoryInterface::class);
        $categories = $repo->allBy(['status' => 1, 'slug' => $slug], [], ['id', 'name', 'parent_id']);
        return $categories;
    }
}

if (!function_exists('register_post_format')) {
    /**
     * @param array $formats
     * @return void
     * @author Sang Nguyen
     */
    function register_post_format(array $formats)
    {
        PostFormat::registerPostFormat($formats);
    }
}

if (!function_exists('get_post_formats')) {
    /**
     * @param bool $convert_to_list
     * @return array
     * @author Sang Nguyen
     */
    function get_post_formats($convert_to_list = false)
    {
        return PostFormat::getPostFormats($convert_to_list);
    }
}