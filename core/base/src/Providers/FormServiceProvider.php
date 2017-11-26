<?php

namespace Botble\Base\Providers;

use Form;
use Illuminate\Support\ServiceProvider;

class FormServiceProvider extends ServiceProvider
{

    /**
     * Boot the service provider.
     * @return void
     * @author Sang Nguyen
     */
    public function boot()
    {

        Form::component('mediaImage', 'bases::elements.forms.image', [
            'name',
            'value' => null,
            'attributes' => [],
        ]);

        Form::component('permalink', 'bases::elements.forms.permalink', [
            'name',
            'value' => null,
            'id' => null,
            'url' => null,
            'preview' => route('public.single.detail', config('cms.slug.pattern')),
            'default_slug' => '',
            'attributes' => [],
        ]);

        Form::component('modalAction', 'bases::elements.forms.modal', [
            'name',
            'title',
            'type' => null,
            'content' => null,
            'action_id' => null,
            'action_name' => null,
        ]);

        Form::component('helper', 'bases::elements.forms.helper', ['content']);

        Form::component('onOff', 'bases::elements.forms.on-off', [
            'name',
            'value' => false,
            'attributes' => [],
        ]);

        /**
         * Custom checkbox
         * Every checkbox will not have the same name
         */
        Form::component('customCheckbox', 'bases::elements.custom-checkbox', [
            /**
             * @var array $values
             * @template: [
             *      [string $name, string $value, string $label, bool $selected, bool $disabled],
             *      [string $name, string $value, string $label, bool $selected, bool $disabled],
             *      [string $name, string $value, string $label, bool $selected, bool $disabled],
             * ]
             */
            'values',
        ]);

        /**
         * Custom radio
         * Every radio in list must have the same name
         */
        Form::component('customRadio', 'bases::elements.custom-radio', [
            /**
             * @var string $name
             */
            'name',
            /**
             * @var array $values
             * @template: [
             *      [string $value, string $label, bool $disabled],
             *      [string $value, string $label, bool $disabled],
             *      [string $value, string $label, bool $disabled],
             * ]
             */
            'values',
            /**
             * @var string|null $selected
             */
            'selected' => null,
        ]);

        Form::component('error', 'bases::elements.forms.error', [
            'name',
            'errors' => null,
        ]);
    }
}
