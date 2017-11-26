<?php

namespace Botble\Blog\Http\Controllers;

use Botble\ACL\Repositories\Interfaces\UserInterface;
use Botble\Base\Supports\Helper;
use Botble\Blog\Repositories\Interfaces\CategoryInterface;
use Botble\Blog\Repositories\Interfaces\PostInterface;
use Botble\Blog\Repositories\Interfaces\TagInterface;
use Botble\Page\Repositories\Interfaces\PageInterface;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use SeoHelper;
use Theme;

class PublicController extends Controller
{
    /**
     * @param $slug
     * @param PostInterface $postRepository
     * @param CategoryInterface $categoryRepository
     * @param PageInterface $pageRepository
     * @return \Response
     * @author Sang Nguyen
     */
    public function getView($slug, $p1 = null, $p2 = null, $p3 = null, PostInterface $postRepository, CategoryInterface $categoryRepository, PageInterface $pageRepository)
    {
        if (!is_null($slug) && !is_null($p1) && !is_null($p2) && !is_null($p3)){
            $p3 = explode('.', $p3);
            $slug = $slug.'/'.$p1.'/'.$p2.'/'.$p3[0];
        }
        elseif (!is_null($slug) && !is_null($p1) && !is_null($p2)){
            $p2 = explode('.', $p2);
            $slug = $slug.'/'.$p1.'/'.$p2[0];        }
        elseif (!is_null($slug) && !is_null($p1)){
            $p1 = explode('.', $p1);
            $slug = $slug.'/'.$p1[0];
        }
        
        $post = $postRepository->getBySlug($slug, true);
        if (!empty($post)) {

            Helper::handleViewCount($post, 'viewed_post');

            SeoHelper::setTitle($post->name)->setDescription($post->description);

            admin_bar()->registerLink(trans('blog::posts.edit_this_post'), route('posts.edit', $post->id));

            Theme::breadcrumb()->add(__('Home'), route('public.index'))->add($post->name, route('public.single.detail', $slug));

            do_action(BASE_ACTION_PUBLIC_RENDER_SINGLE, POST_MODULE_SCREEN_NAME, $post);

            return Theme::scope('post', compact('post'))->render();
        }

        $page = $pageRepository->getBySlug($slug, true);
        if (!empty($page)) {
            SeoHelper::setTitle($page->name)->setDescription($page->description);

            if ($page->template) {
                Theme::uses(setting('theme'))->layout($page->template);
            }

            admin_bar()->registerLink(trans('pages::pages.edit_this_page'), route('pages.edit', $page->id));

            Theme::breadcrumb()->add(__('Home'), route('public.index'))->add($page->name, route('public.single.detail', $slug));

            do_action(BASE_ACTION_PUBLIC_RENDER_SINGLE, PAGE_MODULE_SCREEN_NAME, $page);
            return Theme::scope('page', compact('page'))->render();
        }

        $category = $categoryRepository->getBySlug($slug, true);
        if (!empty($category)) {
            SeoHelper::setTitle($category->name)->setDescription($category->description);

            admin_bar()->registerLink(trans('blog::categories.edit_this_category'), route('categories.edit', $category->id));

            $allRelatedCategoryIds = array_unique(array_merge($categoryRepository->getAllRelatedChildrenIds($category), [$category->id]));

            $posts = $postRepository->getByCategory($allRelatedCategoryIds, 12);

            Theme::breadcrumb()->add(__('Home'), route('public.index'))->add($category->name, route('public.single.detail', $slug));

            do_action(BASE_ACTION_PUBLIC_RENDER_SINGLE, CATEGORY_MODULE_SCREEN_NAME, $category);
            return Theme::scope('category', compact('category', 'posts'))->render();
        }

        return abort(404);
    }

    /**
     * @param $slug
     * @param TagInterface $tagRepository
     * @return \Response
     * @author Sang Nguyen
     */
    public function getByTag($slug, TagInterface $tagRepository)
    {
        $tag = $tagRepository->getBySlug($slug, true);

        if (!$tag) {
            return abort(404);
        }

        SeoHelper::setTitle($tag->name)->setDescription($tag->description);

        admin_bar()->registerLink(trans('blog::tags.edit_this_tag'), route('tags.edit', $tag->id));

        $posts = get_posts_by_tag($tag->slug);

        Theme::breadcrumb()->add(__('Home'), route('public.index'))->add($tag->name, route('public.tag', $slug));

        do_action(BASE_ACTION_PUBLIC_RENDER_SINGLE, TAG_MODULE_SCREEN_NAME, $tag);
        return Theme::scope('tag', compact('tag', 'posts'))->render();
    }

    /**
     * @param Request $request
     * @param PostInterface $postRepository
     * @param PageInterface $pageRepository
     * @return array
     * @author Sang Nguyen
     */
    public function getApiSearch(Request $request, PostInterface $postRepository, PageInterface $pageRepository)
    {
        $query = $request->get('q');
        if (!empty($query)) {

            $posts = $postRepository->getSearch($query);
            $pages = $pageRepository->getSearch($query);

            $data = [
                'items' => [
                    'Posts' => Theme::partial('search.post', compact('posts')),
                    'Pages' => Theme::partial('search.page', compact('pages')),
                ],
                'query' => $query,
                'count' => $posts->count() + $pages->count(),
            ];

            if ($data['count'] > 0) {
                return [
                    'error' => false,
                    'data' => apply_filters(BASE_FILTER_SET_DATA_SEARCH, $data, 10, 1),
                ];
            }

        }
        return [
            'error' => true,
            'message' => trans('bases::layouts.no_search_result'),
        ];
    }

    /**
     * @param Request $request
     * @param PostInterface $postRepository
     * @return \Response
     */
    public function getSearch(Request $request, PostInterface $postRepository)
    {
        SeoHelper::setTitle(__('Search result for: ') . '"' . $request->get('q') . '"')->setDescription(__('Search result for: ') . '"' . $request->get('q') . '"');

        $posts = $postRepository->getSearch($request->get('q'), 0, 12);

        Theme::breadcrumb()->add(__('Home'), route('public.index'))->add(__('Search result for: ') . '"' . $request->get('q') . '"', route('public.search'));
        return Theme::scope('search', compact('posts'))->render();
    }

    /**
     * @param $slug
     * @param UserInterface $userRepository
     * @return \Response
     * @author Sang Nguyen
     */
    public function getAuthor($slug, UserInterface $userRepository)
    {
        $author = $userRepository->getFirstBy(['username' => $slug]);
        if (!$author) {
            return abort(404);
        }

        admin_bar()->registerLink('Edit this user', route('user.profile.view', $author->id));

        SeoHelper::setTitle($author->getFullName())->setDescription($author->about);
        Theme::breadcrumb()->add(__('Home'), route('public.index'))->add($author->getFullName(), route('public.author', $slug));

        do_action(BASE_ACTION_PUBLIC_RENDER_SINGLE, USER_MODULE_SCREEN_NAME, $author);
        return Theme::scope('author', compact('author'))->render();
    }

    /**
     * Generate JSON feed
     * @return array
     * @author Sang Nguyen
     */
    public function getJsonFeed()
    {
        admin_bar()->setDisplay(false);

        $data = [
            'version' => 'https://jsonfeed.org/version/1',
            'title' => 'Json Feed',
            'home_page_url' => route('public.index'),
            'feed_url' => route('public.blog.feed.json'),
            'icon' => Theme::asset()->url('images/favicon.png'),
            'favicon' => Theme::asset()->url('images/favicon.png'),
            'items' => [],
        ];

        foreach (get_all_posts(true) as $post) {
            $data['items'][] = [
                'id' => $post->id,
                'title' => $post->name,
                'url' => route('public.single.detail', $post->slug),
                'image' => $post->image,
                'content_html' => $post->content,
                'date_published' => $post->created_at->tz('UTC')->toRfc3339String(),
                'date_modified' => $post->updated_at->tz('UTC')->toRfc3339String(),
                'author' => [
                    'name' => $post->author ? $post->author->name : null,
                ],
            ];
        }

        foreach (get_all_categories(['status' => 1]) as $category) {
            $data['items'][] = [
                'id' => $category->id,
                'title' => $category->name,
                'url' => route('public.single.detail', $category->slug),
                'image' => null,
                'content_html' => $category->description,
                'date_published' => $category->created_at->tz('UTC')->toRfc3339String(),
                'date_modified' => $category->updated_at->tz('UTC')->toRfc3339String(),
            ];
        }

        foreach (get_all_tags(true) as $tag) {
            $data['items'][] = [
                'id' => $tag->id,
                'title' => $tag->name,
                'url' => route('public.tag', $tag->slug),
                'image' => null,
                'content_html' => $tag->description,
                'date_published' => $tag->created_at->tz('UTC')->toRfc3339String(),
                'date_modified' => $tag->updated_at->tz('UTC')->toRfc3339String(),
            ];
        }

        return $data;
    }
}
