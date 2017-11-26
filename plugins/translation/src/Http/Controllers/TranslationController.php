<?php

namespace Botble\Translation\Http\Controllers;

use Artisan;
use Assets;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Translation\Manager;
use Exception;
use Illuminate\Http\Request;
use Botble\Translation\Models\Translation;
use Illuminate\Support\Collection;

class TranslationController extends BaseController
{
    /**
     * @var Manager
     */
    protected $manager;

    /**
     * TranslationController constructor.
     * @param Manager $manager
     * @author Barry vd. Heuvel <barryvdh@gmail.com>
     */
    public function __construct(Manager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @param null $group
     * @return string
     * @author Barry vd. Heuvel <barryvdh@gmail.com>
     */
    public function getIndex($group = null)
    {
        page_title()->setTitle(trans('translations::translation.translation_manager'));

        Assets::addJavascript(['bootstrap-editable'])
            ->addStylesheets(['bootstrap-editable']);
        Assets::addJavascriptDirectly(config('translation.assets_dir') . '/js/translation.js');

        $locales = $this->loadLocales();
        $groups = Translation::groupBy('group');
        $excludedGroups = config('translation.exclude_groups');
        if ($excludedGroups) {
            $groups->whereNotIn('group', $excludedGroups);
        }

        $groups = $groups->pluck('group', 'group');
        if ($groups instanceof Collection) {
            $groups = $groups->all();
        }
        $groups = ['' => trans('translations::translation.choose_a_group')] + $groups;
        $numChanged = Translation::where('group', $group)->where('status', Translation::STATUS_CHANGED)->count();


        $allTranslations = Translation::where('group', $group)->orderBy('key', 'asc')->get();
        $numTranslations = count($allTranslations);
        $translations = [];
        foreach ($allTranslations as $translation) {
            $translations[$translation->key][$translation->locale] = $translation;
        }

        return view('translations::index')
            ->with('translations', $translations)
            ->with('locales', $locales)
            ->with('groups', $groups)
            ->with('group', $group)
            ->with('numTranslations', $numTranslations)
            ->with('numChanged', $numChanged)
            ->with('editUrl', route('translations.group.edit') . '?group=' . $group)
            ->with('deleteEnabled', config('translation.delete_enabled'));
    }

    /**
     * @param Request $request
     * @return string
     * @author Barry vd. Heuvel <barryvdh@gmail.com>
     */
    public function getView(Request $request)
    {
        return $this->getIndex($request->input('file'));
    }

    /**
     * @return array
     * @author Barry vd. Heuvel <barryvdh@gmail.com>
     */
    protected function loadLocales()
    {
        //Set the default locale as the first one.
        $locales = Translation::select('locale')->distinct()->get()->pluck('locale');

        if ($locales instanceof Collection) {
            $locales = $locales->all();
        }
        $locales = array_merge([config('app.locale')], $locales);
        return array_unique($locales);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @author Barry vd. Heuvel <barryvdh@gmail.com>
     */
    public function postAdd(Request $request)
    {
        $keys = explode(PHP_EOL, $request->get('keys'));

        $group =  $request->input('group');

        foreach ($keys as $key) {
            $key = trim($key);
            if ($group && $key) {
                $this->manager->missingKey($group, $key);
            }
        }
        return redirect()->back();
    }

    /**
     * @param Request $request
     * @return array|string
     * @author Barry vd. Heuvel <barryvdh@gmail.com>
     */
    public function postEdit(Request $request)
    {
        $group = $request->input('group');
        if (empty(config('translation.exclude_groups')) || !in_array($group, config('translation.exclude_groups'))) {
            $name = $request->get('name');
            $value = $request->get('value');

            list($locale, $key) = explode('|', $name, 2);
            try {
                $translation = Translation::firstOrNew([
                    'locale' => $locale,
                    'group' => $group,
                    'key' => $key,
                ]);

                $translation->value = (string) $value;
                $translation->status = Translation::STATUS_CHANGED;
                $translation->save();
            } catch (Exception $ex) {
                return $ex->getMessage();
            }
        }
        return [
            'status' => 'ok',
        ];
    }

    /**
     * @param Request $request
     * @return array
     * @author Barry vd. Heuvel <barryvdh@gmail.com>
     */
    public function postDelete(Request $request)
    {
        if (empty(config('translation.exclude_groups')) || !in_array($request->input('group'), config('translation.exclude_groups')) && config('translation.delete_enabled')) {
            Translation::where('group', $request->input('group'))->where('key', $request->input('key'))->delete();
        }
        return [
            'status' => 'ok',
        ];
    }

    /**
     * @param Request $request
     * @return array
     * @author Barry vd. Heuvel <barryvdh@gmail.com>
     */
    public function postImport(Request $request)
    {
        $replace = $request->get('replace', false);
        $counter = $this->manager->importTranslations($replace);

        return [
            'status' => 'ok',
            'counter' => $counter,
        ];
    }

    /**
     * @return array
     * @author Barry vd. Heuvel <barryvdh@gmail.com>
     */
    public function postFind()
    {
        $numFound = Artisan::call('translations:find');

        return [
            'status' => 'ok',
            'counter' => (int) $numFound,
        ];
    }

    /**
     * @param Request $request
     * @return array|string
     * @author Barry vd. Heuvel <barryvdh@gmail.com>
     */
    public function postPublish(Request $request)
    {
        try {
            $this->manager->exportTranslations($request->input('group'));
        } catch (Exception $ex) {
            return $ex->getMessage();
        }

        return [
            'status' => 'ok',
        ];
    }
}
