<?php

namespace Botble\Translation;

use File;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Events\Dispatcher;
use Botble\Translation\Models\Translation;
use Illuminate\Foundation\Application;
use DB;
use Lang;

class Manager
{

    /**
     * @var Application
     */
    protected $app;

    /**
     * @var Filesystem
     */
    protected $files;

    /**
     * @var Dispatcher
     */
    protected $events;

    /**
     * @var array
     */
    protected $config;

    /**
     * Manager constructor.
     * @param Application $app
     * @param Filesystem $files
     * @param Dispatcher $events
     * @author Barry vd. Heuvel <barryvdh@gmail.com>
     */
    public function __construct(Application $app, Filesystem $files, Dispatcher $events)
    {
        $this->app = $app;
        $this->files = $files;
        $this->events = $events;
        $this->config = $app['config']['translation-manager'];
    }

    /**
     * @param bool $replace
     * @return int
     * @author Barry vd. Heuvel <barryvdh@gmail.com>
     */
    public function importTranslations($replace = false)
    {
        $counter = 0;
        foreach ($this->files->directories($this->app->langPath()) as $langPath) {
            $locale = basename($langPath);

            foreach ($this->files->allFiles($langPath) as $file) {

                $group = File::name($file);

                if (in_array($group, array_get($this->config, 'exclude_groups', []))) {
                    continue;
                }

                $subLangPath = str_replace($langPath . DIRECTORY_SEPARATOR, '', File::dirname($file));

                $lang_directory = $group;
                if ($subLangPath != $langPath) {
                    $lang_directory = $subLangPath . '/' . $group;
                    $group = substr($subLangPath, 0, -3) . '/' . $group;
                }

                $translations = Lang::getLoader()->load($locale, $lang_directory);
                if ($translations && is_array($translations)) {
                    foreach (array_dot($translations) as $key => $value) {
                        // process only string values
                        if (is_array($value)) {
                            continue;
                        }
                        $value = (string)$value;
                        $translation = Translation::firstOrNew([
                            'locale' => $locale != 'vendor' ? $locale : substr($subLangPath, -2),
                            'group' => $group,
                            'key' => $key,
                        ]);

                        // Check if the database is different then the files
                        $newStatus = $translation->value === $value ? Translation::STATUS_SAVED : Translation::STATUS_CHANGED;
                        if ($newStatus !== (int)$translation->status) {
                            $translation->status = $newStatus;
                        }

                        // Only replace when empty, or explicitly told so
                        if ($replace || !$translation->value) {
                            $translation->value = $value;
                        }

                        $translation->save();

                        $counter++;
                    }
                }
            }
        }
        return $counter;
    }

    /**
     * @param $group
     * @param $key
     * @author Barry vd. Heuvel <barryvdh@gmail.com>
     */
    public function missingKey($group, $key)
    {
        if (!in_array($group, config('translations.exclude_groups', []))) {
            Translation::firstOrCreate([
                'locale' => $this->app['config']['app.locale'],
                'group' => $group,
                'key' => $key,
            ]);
        }
    }

    /**
     * @param $group
     * @return boolean
     * @author Barry vd. Heuvel <barryvdh@gmail.com>
     */
    public function exportTranslations($group)
    {
        if (empty($this->config['exclude_groups']) || !in_array($group, $this->config['exclude_groups'])) {
            if ($group == '*') {
                $this->exportAllTranslations();
                return true;
            }

            $tree = $this->makeTree(Translation::where('group', $group)->whereNotNull('value')->get());

            foreach ($tree as $locale => $groups) {
                if (isset($groups[$group])) {
                    $translations = $groups[$group];
                    $file = $locale . '/' . $group;
                    $groups = explode('/', $group);
                    if (count($groups) > 1) {
                        $dir = '/vendor/' . $groups[0] . '/' . $locale;
                        if (!$this->files->isDirectory($this->app->langPath() . '/' . $dir)) {
                            $this->files->makeDirectory($this->app->langPath() . '/' . $dir, 755, true);
                            system('find ' . $this->app->langPath() . '/' . $dir . ' -type d -exec chmod 755 {} \;');
                        }
                        $file = $dir . '/' . $groups[1];
                    }
                    $path = $this->app->langPath() . '/' . $file . '.php';
                    $output = "<?php\n\nreturn " . var_export($translations, true) . ";\n";
                    $this->files->put($path, $output);
                }
            }
            Translation::where('group', $group)->whereNotNull('value')->update(['status' => Translation::STATUS_SAVED]);
        }
        return true;
    }

    /**
     * @author Barry vd. Heuvel <barryvdh@gmail.com>
     */
    public function exportAllTranslations()
    {
        switch (DB::getDriverName()) {
            case 'mysql':
                $select = 'DISTINCT `group`';
                break;

            default:
                $select = 'DISTINCT "group"';
                break;
        }

        $groups = Translation::whereNotNull('value')->select(DB::raw($select))->get('group');

        foreach ($groups as $group) {
            $this->exportTranslations($group->group);
        }
    }

    /**
     * @param $translations
     * @return array
     * @author Barry vd. Heuvel <barryvdh@gmail.com>
     */
    protected function makeTree($translations)
    {
        $array = [];
        foreach ($translations as $translation) {
            array_set($array[$translation->locale][$translation->group], $translation->key, $translation->value);
        }
        return $array;
    }

    /**
     * @author Barry vd. Heuvel <barryvdh@gmail.com>
     */
    public function cleanTranslations()
    {
        Translation::whereNull('value')->delete();
    }

    /**
     * @author Barry vd. Heuvel <barryvdh@gmail.com>
     */
    public function truncateTranslations()
    {
        Translation::truncate();
    }

    /**
     * @param null $key
     * @return mixed
     * @author Barry vd. Heuvel <barryvdh@gmail.com>
     */
    public function getConfig($key = null)
    {
        if ($key == null) {
            return $this->config;
        }

        return $this->config[$key];
    }
}
