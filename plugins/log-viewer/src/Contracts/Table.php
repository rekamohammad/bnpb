<?php

namespace Botble\LogViewer\Contracts;

interface Table
{
    /**
     * Get table header.
     *
     * @return array
     * @author ARCANEDEV <arcanedev.maroc@gmail.com>
     */
    public function header();

    /**
     * Get table rows.
     *
     * @return array
     * @author ARCANEDEV <arcanedev.maroc@gmail.com>
     */
    public function rows();

    /**
     * Get table footer.
     *
     * @return array
     * @author ARCANEDEV <arcanedev.maroc@gmail.com>
     */
    public function footer();
}
