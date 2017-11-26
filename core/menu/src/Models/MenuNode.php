<?php

namespace Botble\Menu\Models;

use Eloquent;
use stdClass;

class MenuNode extends Eloquent
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'menu_nodes';

    /**
     * @return mixed
     * @author Sang Nguyen
     */
    public function menuContent()
    {
        return $this->belongsTo(MenuContent::class, 'menu_content_id');
    }

    /**
     * @return mixed
     * @author Sang Nguyen
     */
    public function parent()
    {
        return $this->belongsTo(MenuNode::class, 'parent_id');
    }

    /**
     * @return mixed
     * @author Sang Nguyen
     */
    public function child()
    {
        return $this->hasMany(MenuNode::class, 'parent_id');
    }

    /**
     * @param $theme
     * @return mixed
     * @author Sang Nguyen
     */
    public function getRelated($theme = false)
    {
        $item = new stdClass;
        $item->name = $this->title;
        $item->url = $this->url ? url($this->url) : '';
        return apply_filters(MENU_FILTER_MENU_ITEM, $item, [
            'type' => $this->type,
            'title' => $this->title,
            'related_id' => $this->related_id,
            'theme' => $theme,
        ]);
    }

    /**
     * @return bool
     * @author Sang Nguyen
     */
    public function hasChild()
    {
        $menu = MenuNode::where('parent_id', $this->id)->select('id')->first();
        if ($menu) {
            return true;
        }

        return false;
    }
}
