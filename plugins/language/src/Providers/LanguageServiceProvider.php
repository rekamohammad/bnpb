<?php

namespace Botble\Language\Providers;

use Assets;
use Botble\Base\Events\SessionStarted;
use Botble\Base\Supports\Helper;
use Botble\Language\Facades\LanguageFacade;
use Botble\Language\Http\Middleware\LocaleSessionRedirect;
use Botble\Language\Http\Middleware\LocalizationRedirectFilter;
use Botble\Language\Http\Middleware\LocalizationRoutes;
use Botble\Language\Models\Language as LanguageModel;
use Botble\Language\Models\LanguageMeta;
use Botble\Language\Repositories\Caches\LanguageMetaCacheDecorator;
use Botble\Language\Repositories\Eloquent\LanguageMetaRepository;
use Botble\Language\Repositories\Interfaces\LanguageMetaInterface;
use Event;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use Botble\Language\Repositories\Caches\LanguageCacheDecorator;
use Botble\Language\Repositories\Eloquent\LanguageRepository;
use Botble\Language\Repositories\Interfaces\LanguageInterface;
use Botble\Support\Services\Cache\Cache;
use Language;
use Request;
use Route;

class LanguageServiceProvider extends ServiceProvider
{
    /**
     * @var \Illuminate\Foundation\Application
     */
    protected $app;

    /**
     * @author Sang Nguyen
     */
    public function register()
    {
        if (setting('enable_cache', false)) {
            $this->app->singleton(LanguageInterface::class, function () {
                return new LanguageCacheDecorator(new LanguageRepository(new LanguageModel()), new Cache($this->app['cache'], LanguageRepository::class));
            });

            $this->app->singleton(LanguageMetaInterface::class, function () {
                return new LanguageMetaCacheDecorator(new LanguageMetaRepository(new LanguageMeta()), new Cache($this->app['cache'], LanguageMetaRepository::class));
            });
        } else {
            $this->app->singleton(LanguageInterface::class, function () {
                return new LanguageRepository(new LanguageModel());
            });

            $this->app->singleton(LanguageMetaInterface::class, function () {
                return new LanguageMetaRepository(new LanguageMeta());
            });
        }

        Helper::autoload(__DIR__ . '/../../helpers');

        AliasLoader::getInstance()->alias('Language', LanguageFacade::class);

        $this->app['router']->aliasMiddleware('localize', LocalizationRoutes::class);
        $this->app['router']->aliasMiddleware('localizationRedirect', LocalizationRedirectFilter::class);
        $this->app['router']->aliasMiddleware('localeSessionRedirect', LocaleSessionRedirect::class);
    }

    /**
     * @author Sang Nguyen
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/../../routes/web.php');
        $this->mergeConfigFrom(__DIR__ . '/../../config/language.php', 'language');
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'language');
        $this->loadTranslationsFrom(__DIR__ . '/../../resources/lang', 'language');

        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');

            $this->publishes([__DIR__ . '/../../resources/views' => resource_path('views/vendor/language')], 'views');
            $this->publishes([__DIR__ . '/../../resources/lang' => resource_path('lang/vendor/language')], 'lang');
            $this->publishes([__DIR__ . '/../../config/language.php' => config_path('language.php')], 'config');
            $this->publishes([__DIR__ . '/../../resources/assets' => resource_path('assets/core/plugins/language')], 'resources');
            $this->publishes([__DIR__ . '/../../public/assets' => public_path('vendor/core/plugins/language')], 'assets');
        } else {
            Event::listen(SessionStarted::class, function () {
                dashboard_menu()->registerItem([
                    'id' => 'cms-plugins-language',
                    'priority' => 2,
                    'parent_id' => 'cms-core-settings',
                    'name' => trans('language::language.menu'),
                    'icon' => null,
                    'url' => route('languages.list'),
                    'permissions' => ['languages.list'],
                ]);
            });

            Assets::addJavascriptDirectly('vendor/core/plugins/language/js/language-global.js');
            $default_language = Language::getDefaultLanguage(['lang_id']);
            if (!empty($default_language)) {
                add_action(BASE_ACTION_META_BOXES, [$this, 'addLanguageBox'], 50, 3);
                add_action(BASE_ACTION_AFTER_CREATE_CONTENT, [$this, 'saveLanguageData'], 55, 3);
                add_action(BASE_ACTION_AFTER_UPDATE_CONTENT, [$this, 'saveLanguageData'], 55, 3);
                add_action(BASE_ACTION_AFTER_DELETE_CONTENT, [$this, 'deleteLanguageMeta'], 55, 2);
                add_action(BASE_ACTION_CREATE_CONTENT_NOTIFICATION, [$this, 'addCurrentLanguageEditingAlert'], 55, 3);
                add_action(BASE_ACTION_EDIT_CONTENT_NOTIFICATION, [$this, 'addCurrentLanguageEditingAlert'], 55, 3);
            }

            add_filter(BASE_FILTER_GET_LIST_DATA, [$this, 'addLanguageColumn'], 50, 2);
            add_filter(BASE_FILTER_TABLE_HEADINGS, [$this, 'addLanguageTableHeading'], 50, 2);
            add_filter(LANGUAGE_FILTER_SWITCHER, [$this, 'languageSwitcher']);
            add_filter(BASE_FILTER_BEFORE_GET_FRONT_PAGE_ITEM, [$this, 'checkItemLanguageBeforeShow'], 50, 3);
            add_filter(BASE_FILTER_BEFORE_GET_BY_SLUG, [$this, 'getRelatedDataForOtherLanguage'], 50, 3);
            add_filter(BASE_FILTER_BEFORE_GET_SINGLE, [$this, 'getRelatedDataForOtherLanguage'], 50, 4);
            add_filter(BASE_FILTER_GROUP_PUBLIC_ROUTE, [$this, 'addLanguageMiddlewareToPublicRoute'], 958, 1);
            add_filter(BASE_FILTER_DATATABLES_BUTTONS, [$this, 'addLanguageSwitcherToTable'], 247, 2);
            add_filter(BASE_FILTER_DATATABLES_QUERY, [$this, 'getDataByCurrentLanguage'], 157, 3);
        }
    }

    /**
     * @param $screen
     * @author Sang Nguyen
     */
    public function addLanguageBox($screen)
    {
        if (in_array($screen, Language::screenUsingMultiLanguage())) {
            add_meta_box('language_wrap', trans('language::language.name'), [$this, 'languageMetaField'], $screen, 'top', 'default');
        }
    }

    /**
     * @author Sang Nguyen
     */
    public function languageMetaField()
    {
        $languages = Language::getActiveLanguage([
            'lang_code',
            'lang_flag',
            'lang_name',
        ]);
        if ($languages->isEmpty()) {
            return null;
        }

        $related = [];
        $value = null;
        $args = func_get_args();

        $meta = null;

        $current_route = explode('.', Route::currentRouteName());
        $route = [
            'create' => Route::currentRouteName(),
            'edit' => $current_route[0] . '.' . 'edit'
        ];

        if (!empty($args[0])) {
            $meta = app(LanguageMetaInterface::class)->getFirstBy([
                'lang_meta_content_id' => $args[0]->id,
                'lang_meta_reference' => $args[1]],
                [
                    'lang_meta_code',
                    'lang_meta_content_id',
                    'lang_meta_origin',
                ]
            );
            if (!empty($meta)) {
                $value = $meta->lang_meta_code;
            }

            $current_route = $current_route = explode('.', Route::currentRouteName());

            if (count($current_route) > 2) {
                $route = $current_route[0] . '.' . $current_route[1];
            } else {
                $route = $current_route[0];
            }

            $route = [
                'create' => $route . '.' . 'create',
                'edit' => Route::currentRouteName()
            ];
        } elseif (Request::get('ref_from')) {
            $meta = app(LanguageMetaInterface::class)->getFirstBy([
                'lang_meta_content_id' => Request::get('ref_from'),
                'lang_meta_reference' => $args[1]],
                [
                    'lang_meta_code',
                    'lang_meta_content_id',
                    'lang_meta_origin',
                ]
            );
            $value = Request::get('ref_lang');
        }
        if ($meta) {
            $related = Language::getRelatedLanguageItem($meta->lang_meta_content_id, $meta->lang_meta_origin);
        }
        $current_language = self::checkCurrentLanguage($languages, $value);

        if (!$current_language) {
            $current_language = Language::getDefaultLanguage([
                    'lang_flag',
                    'lang_name',
                    'lang_code',
                ]
            );
        }

        $route = apply_filters(LANGUAGE_FILTER_ROUTE_ACTION, $route);
        return view('language::language-box', compact('args', 'languages', 'current_language', 'related', 'route'))->render();
    }

    /**
     * @param $screen
     * @param Request $request
     * @param $object
     * @return mixed
     * @author Sang Nguyen
     */
    public function saveLanguageData($screen, $request, $object)
    {
        if (in_array($screen, Language::screenUsingMultiLanguage())) {
            if ($request->input('language')) {
                $unique_key = null;
                $meta = app(LanguageMetaInterface::class)->getFirstBy([
                        'lang_meta_content_id' => $object->id,
                        'lang_meta_reference' => $screen,
                    ]
                );
                if (!$meta && !$request->input('ref_from')) {
                    $unique_key = md5($object->id . $screen . time());
                } elseif ($request->input('ref_from')) {
                    $unique_key = app(LanguageMetaInterface::class)->getFirstBy([
                            'lang_meta_content_id' => $request->input('ref_from'),
                            'lang_meta_reference' => $screen,
                        ]
                    )->lang_meta_origin;
                }

                if (!$meta) {
                    $meta = app(LanguageMetaInterface::class)->getModel();
                    $meta->lang_meta_content_id = $object->id;
                    $meta->lang_meta_reference = $screen;
                    $meta->lang_meta_origin = $unique_key;
                }

                $meta->lang_meta_code = $request->input('language');
                app(LanguageMetaInterface::class)->createOrUpdate($meta);
                return true;
            }
        }
        return false;
    }

    /**
     * @param $value
     * @param $languages
     * @return mixed
     * @author Sang Nguyen
     */
    public function checkCurrentLanguage($languages, $value)
    {
        $current_language = null;
        foreach ($languages as $language) {
            if ($value) {
                if ($language->lang_code == $value) {
                    $current_language = $language;
                }
            } else {
                if (Request::get('ref_lang')) {
                    if ($language->lang_code == Request::get('ref_lang')) {
                        $current_language = $language;
                    }
                } else {
                    if ($language->lang_is_default) {
                        $current_language = $language;
                    }
                }
            }
        }

        if (empty($current_language)) {
            foreach ($languages as $language) {
                if ($language->lang_is_default) {
                    $current_language = $language;
                }
            }
        }

        return $current_language;
    }

    /**
     * @param $content
     * @param $screen
     * @return mixed
     * @author Sang Nguyen
     */
    public function deleteLanguageMeta($screen, $content)
    {
        if (in_array($screen, Language::screenUsingMultiLanguage())) {
            app(LanguageMetaInterface::class)->deleteBy([
                    'lang_meta_content_id' => $content->id,
                    'lang_meta_reference' => $screen,
                ]
            );
        }
        return true;
    }

    /**
     * @param $screen
     * @param Request $request
     * @param $data
     * @return void
     * @author Sang Nguyen
     * @since 2.1
     */
    public function addCurrentLanguageEditingAlert($screen, $request, $data = null)
    {
        if (in_array($screen, Language::screenUsingMultiLanguage())) {
            $code = null;
            $language = null;
            if ($request->has('ref_lang')) {
                $code = $request->input('ref_lang');
            } else {
                if (!empty($data)) {
                    $meta = app(LanguageMetaInterface::class)->getFirstBy([
                        'lang_meta_content_id' => $data->id,
                        'lang_meta_reference' => $screen,
                    ], ['lang_meta_code']);
                    if (!empty($meta)) {
                        $code = $meta->lang_meta_code;
                    }
                }
            }

            if (empty($code)) {
                $code = Language::getDefaultLocaleCode();
            }

            if (!empty($code)) {
                $language = app(LanguageInterface::class)->getFirstBy(['lang_code' => $code], ['lang_name']);
                if (!empty($language)) {
                    $language = $language->lang_name;
                }
            }
            echo view('language::partials.notification', compact('language'))->render();
        }
        echo null;
    }


    /**
     * @param $headings
     * @param $screen
     * @return mixed
     * @author Sang Nguyen
     */
    public function addLanguageTableHeading($headings, $screen)
    {
        if (in_array($screen, Language::screenUsingMultiLanguage())) {
            $languages = Language::getActiveLanguage(['lang_flag', 'lang_name']);
            $heading = '';
            foreach ($languages as $language) {
                $heading .= language_flag($language->lang_flag, $language->lang_name);
            }
            return array_merge($headings, [
                'language' => [
                    'name' => 'language_meta.lang_meta_id',
                    'title' => $heading,
                    'class' => 'text-center language-header',
                ],
            ]);
        }
        return $headings;
    }

    /**
     * @param $data
     * @param $screen
     * @return mixed
     * @author Sang Nguyen
     */
    public function addLanguageColumn($data, $screen)
    {
        if (in_array($screen, Language::screenUsingMultiLanguage())) {
            return $data->addColumn('language', function ($item) use ($screen) {
                $current_language = app(LanguageMetaInterface::class)->getFirstBy([
                    'lang_meta_content_id' => $item->id,
                    'lang_meta_reference' => $screen,
                ]);
                $related_languages = [];
                if ($current_language) {
                    $related_languages = Language::getRelatedLanguageItem($current_language->lang_meta_content_id, $current_language->lang_meta_origin);
                    $current_language = $current_language->lang_meta_code;
                }
                $languages = Language::getActiveLanguage();
                $data = '';
                $current_route = explode('.', Route::currentRouteName());

                if (count($current_route) > 2) {
                    $route = $current_route[0] . '.' . $current_route[1];
                } else {
                    $route = $current_route[0];
                }

                foreach ($languages as $language) {
                    if ($language->lang_code == $current_language) {
                        $data .= view('language::partials.status.active', compact('route', 'item'))->render();
                    } else {
                        $added = false;
                        if (!empty($related_languages)) {
                            foreach ($related_languages as $key => $related_language) {
                                if ($key == $language->lang_code) {
                                    $data .= view('language::partials.status.edit', compact('route', 'related_language'))->render();
                                    $added = true;
                                }
                            }
                        }
                        if (!$added) {
                            $data .= view('language::partials.status.plus', compact('route', 'item', 'language'))->render();
                        }
                    }
                }

                return view('language::partials.language-column', compact('data'))->render();
            });
        }
        return $data;
    }

    /**
     * @param array $options
     * @return string
     * @author Sang Nguyen
     */
    public function languageSwitcher($options = [])
    {
        return view('language::partials.switcher', compact('options'))->render();
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $data
     * @param $screen
     * @param \Illuminate\Database\Eloquent\Model $model
     * @return mixed
     * @author Sang Nguyen
     */
    public function checkItemLanguageBeforeShow($data, $model, $screen)
    {
        $table = $model->getTable();
        return $data->join('language_meta', 'language_meta.lang_meta_content_id', $table . '.id')
            ->where('language_meta.lang_meta_reference', '=', $screen)
            ->where('language_meta.lang_meta_code', '=', Language::getCurrentLocaleCode());
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $data
     * @param $screen
     * @param \Illuminate\Database\Eloquent\Builder $model
     * @return string
     * @author Sang Nguyen
     */
    public function getRelatedDataForOtherLanguage($data, $model, $screen)
    {
        if (!empty($data)) {
            $current = app(LanguageMetaInterface::class)->getFirstBy([
                'lang_meta_reference' => $screen,
                'lang_meta_content_id' => $data->id,
            ]);
            if ($current) {
                if ($current->lang_meta_code != Language::getCurrentLocaleCode()) {
                    if (config('language.show_item_in_default_language_if_current_version_not_existed', true) == false && $screen != MENU_MODULE_SCREEN_NAME) {
                        return null;
                    }
                    $meta = app(LanguageMetaInterface::class)->getModel()
                        ->where('lang_meta_origin', '=', $current->lang_meta_origin)
                        ->where('lang_meta_content_id', '!=', $data->id)
                        ->where('lang_meta_code', '=', Language::getCurrentLocaleCode())
                        ->first();
                    if ($meta) {
                        $data = $model->where('id', '=', $meta->lang_meta_content_id)->first();
                        if ($data) {
                            return $data;
                        }
                    }
                }
            }
        }
        return $data;
    }

    /**
     * @param $data
     * @return array
     * @author Sang Nguyen
     */
    public function addLanguageMiddlewareToPublicRoute($data)
    {
        return array_merge_recursive($data, [
            'prefix' => Language::setLocale(),
            'middleware' => [
                'localeSessionRedirect',
                'localizationRedirect',
            ],
        ]);
    }

    /**
     * @param $buttons
     * @param $screen
     * @return array
     * @author Sang Nguyen
     * @since 2.2
     */
    public function addLanguageSwitcherToTable($buttons, $screen)
    {
        if (in_array($screen, Language::screenUsingMultiLanguage())) {
            $active_languages = Language::getActiveLanguage(['lang_code', 'lang_name']);
            $language_buttons = [];
            $current_language = Language::getCurrentDataLanguage();
            foreach ($active_languages as $item) {
                $language_buttons[] = [
                    'className' => 'change-data-language-item ' . ($item->lang_code == $current_language ? 'active' : ''),
                    'text' => '<span data-href="' . route('languages.change.data.language', $item->lang_code) . '">' . $item->lang_name . '</span>',
                ];
            }

            $language_buttons[] = [
                'className' => 'change-data-language-item ' . ('all' == $current_language ? 'active' : ''),
                'text' => '<span data-href="' . route('languages.change.data.language', 'all') . '">' . trans('language::language.show_all') . '</span>',
            ];

            $flag = app(LanguageInterface::class)->getFirstBy(['lang_code' => $current_language], ['lang_flag', 'lang_name']);
            if (!empty($flag)) {
                $flag = language_flag($flag->lang_flag, $flag->lang_name);
            } else {
                $flag = '<i class="fa fa-flag"></i>';
            }

            $language = [
                'language' => [
                    'extend' => 'collection',
                    'text' => $flag . ' <span> ' . trans('language::language.change_language') . ' <span class="caret"></span></span>',
                    'buttons' => $language_buttons,
                ],
            ];
            $buttons = array_merge($buttons, $language);
        }

        return $buttons;
    }

    /**
     * @param $query
     * @param $model
     * @param $screen
     * @return mixed
     * @author Sang Nguyen
     * @since 2.2
     */
    public function getDataByCurrentLanguage($query, $model, $screen)
    {
        if (in_array($screen, Language::screenUsingMultiLanguage()) && Language::getCurrentDataLanguage() != 'all') {
            /**
             * @var \Eloquent $model
             */
            $table = $model->getTable();
            $query = $query->join('language_meta', 'language_meta.lang_meta_content_id', $table . '.id')
                ->where('language_meta.lang_meta_reference', '=', $screen)
                ->where('language_meta.lang_meta_code', '=', Language::getCurrentDataLanguage());
        }
        return $query;
    }
}
