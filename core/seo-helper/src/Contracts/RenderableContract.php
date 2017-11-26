<?php

namespace Botble\SeoHelper\Contracts;

interface RenderableContract
{
    /**
     * Render the tag.
     *
     * @return string
     * @author ARCANEDEV <arcanedev.maroc@gmail.com>
     */
    public function render();

    /**
     * Render the tag.
     *
     * @return string
     * @author ARCANEDEV <arcanedev.maroc@gmail.com>
     */
    public function __toString();
}
