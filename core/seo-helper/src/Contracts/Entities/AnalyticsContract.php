<?php

namespace Botble\SeoHelper\Contracts\Entities;

use Botble\SeoHelper\Contracts\RenderableContract;

interface AnalyticsContract extends RenderableContract
{
    /**
     * Set Google Analytics code.
     *
     * @param  string $code
     *
     * @return self
     * @author ARCANEDEV <arcanedev.maroc@gmail.com>
     */
    public function setGoogle($code);
}
