<?php

namespace Botble\Base\Http\Controllers;

use Botble\Page\Repositories\Interfaces\PageInterface;
use Illuminate\Routing\Controller;
use SeoHelper;
use Theme;

class PublicController extends Controller
{

    /**
     * @return mixed
     * @author Sang Nguyen
     */
    public function getIndex()
    {
        Theme::breadcrumb()->add(__('Home'), route('public.index'));
        return Theme::scope('index')->render();
    }

    /**
     * @param $slug
     * @param PageInterface $pageRepository
     * @return \Response
     * @author Sang Nguyen
     */
    public function getView($slug, PageInterface $pageRepository)
    {

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

        return abort(404);
    }

    /**
     * @param PageInterface $pageRepository
     * @return mixed
     * @author Sang Nguyen
     */
    public function getSiteMap(PageInterface $pageRepository)
    {
        // create new site map object
        $site_map = app()->make('sitemap');

        // set cache (key (string), duration in minutes (Carbon|Datetime|int), turn on/off (boolean))
        // by default cache is disabled
        $site_map->setCache('public.sitemap', config('cms.cache_sitemap'));

        // check if there is cached site map and build new only if is not
        if (!$site_map->isCached()) {

            $site_map->add(route('public.index'), '2016-14-20T20:10:00+02:00', '1.0', 'daily');

            // get all pages from db
            $pages = $pageRepository->getDataSiteMap();

            // add every page to the site map
            foreach ($pages as $page) {
                $site_map->add(route('public.single.detail', $page->slug), $page->updated_at, '0.8', 'daily');
            }

            do_action(BASE_ACTION_REGISTER_SITE_MAP, $site_map);
        }

        // show your site map (options: 'xml' (default), 'html', 'txt', 'ror-rss', 'ror-rdf')
        return $site_map->render('xml');
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
            'feed_url' => route('public.feed.json'),
            'icon' => Theme::asset()->url('images/favicon.png'),
            'favicon' => Theme::asset()->url('images/favicon.png'),
            'items' => [],
        ];

        foreach (get_all_pages(true) as $page) {
            $data['items'][] = [
                'id' => $page->id,
                'title' => $page->name,
                'url' => route('public.single.detail', $page->slug),
                'image' => $page->image,
                'content_html' => $page->content,
                'date_published' => $page->created_at->tz('UTC')->toRfc3339String(),
                'date_modified' => $page->updated_at->tz('UTC')->toRfc3339String(),
            ];
        }

        return $data;
    }
}
