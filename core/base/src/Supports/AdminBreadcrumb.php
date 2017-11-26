<?php

namespace Botble\Base\Supports;

use Breadcrumbs;
use URL;

class AdminBreadcrumb
{
    /**
     * @return string
     * @author Sang Nguyen
     */
    public function render()
    {
        return Breadcrumbs::renderIfExists('pageTitle', page_title()->getTitle(false), URL::full());
    }
}