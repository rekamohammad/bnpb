<?php

namespace Botble\CustomField\Models\Contracts;

interface FieldItemContract
{
    /**
     * @return mixed
     */
    public function fieldGroup();

    /**
     * @return mixed
     */
    public function parent();

    /**
     * @return mixed
     */
    public function child();
}
