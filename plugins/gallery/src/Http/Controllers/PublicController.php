<?php

namespace Botble\Gallery\Http\Controllers;

use Botble\Gallery\Repositories\Interfaces\GalleryInterface;
use Illuminate\Routing\Controller;
use Theme;

class PublicController extends Controller
{

    /**
     * @var GalleryInterface
     */
    protected $galleryRepository;

    /**
     * PublicController constructor.
     * @param GalleryInterface $galleryRepository
     * @author Sang Nguyen
     */
    public function __construct(GalleryInterface $galleryRepository)
    {
        $this->galleryRepository = $galleryRepository;
    }

    /**
     * @param $slug
     * @return string
     * @author Sang Nguyen
     */
    public function getGallery($slug)
    {
        $gallery = $this->galleryRepository->getBySlug($slug, true);
        if (empty($gallery)) {
            abort(404);
        }
        Theme::breadcrumb()->add(__('Home'), route('public.index'))->add($gallery->name, route('public.gallery', $slug));

        do_action(BASE_ACTION_PUBLIC_RENDER_SINGLE, GALLERY_MODULE_SCREEN_NAME, $gallery);
        
        // Theme::uses(setting('theme'))->layout('no-sidebar');

        return Theme::scope('gallery', compact('gallery'))->render();
    }

    /**
     * @author Sang Nguyen
     */
    public function getGalleries()
    {
        $galleries = $this->galleryRepository->getAll();
        Theme::breadcrumb()->add(__('Home'), route('public.index'))->add(__('Galleries'), route('public.galleries'));
        return Theme::scope('galleries', compact('galleries'))->render();
    }
}
