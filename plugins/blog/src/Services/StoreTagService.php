<?php

namespace Botble\Blog\Services;

use Botble\Blog\Models\Post;
use Botble\Blog\Services\Abstracts\StoreTagServiceAbstract;
use Illuminate\Http\Request;

class StoreTagService extends StoreTagServiceAbstract
{

    /**
     * @param Request $request
     * @param Post $post
     * @author Sang Nguyen
     * @return mixed|void
     */
    public function execute(Request $request, Post $post)
    {
        $tags = $post->tags->pluck('name')->all();
        if (implode(',', $tags) !== $request->input('tag')) {
            $post->tags()->detach();
            $tagInputs = explode(',', $request->input('tag'));
            foreach ($tagInputs as $tagName) {
                $tag = $this->tagRepository->getFirstBy(['name' => $tagName]);
                if ($tag === null && !empty($tagName)) {
                    $tag = $this->tagRepository->createOrUpdate([
                        'name' => $tagName,
                        'slug' => $this->tagRepository->createSlug($tagName, null),
                        'user_id' => acl_get_current_user_id(),
                    ]);

                    do_action(BASE_ACTION_AFTER_CREATE_CONTENT, TAG_MODULE_SCREEN_NAME, $request, $tag);
                }
                if (!empty($tag)) {
                    $post->tags()->attach($tag->id);
                }
            }
        }
    }
}