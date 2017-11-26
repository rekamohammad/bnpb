<?php

namespace Botble\Base\Commands;

use Artisan;
use Botble\Base\Repositories\Interfaces\PluginInterface;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class PluginDeactivateCommand extends Command
{

    /**
     * The filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;

    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'plugin:deactivate {name : The plugin that you want to deactivate}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deactivate a plugin in /plugins directory';

    /**
     * Create a new key generator command.
     *
     * @param \Illuminate\Filesystem\Filesystem $files
     * @author Sang Nguyen
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();
        $this->files = $files;
    }

    /**
     * @throws Exception
     * @return boolean
     * @author Sang Nguyen
     */
    public function fire()
    {

        if (!preg_match('/^[a-z\-]+$/i', $this->argument('name'))) {
            $this->error('Only alphabetic characters are allowed.');
            return false;
        }

        $plugin_folder = ucfirst(strtolower($this->argument('name')));
        $location = config('cms.plugin_path') . '/' . strtolower($plugin_folder);

        $content = get_file_data($location . '/plugin.json');
        if (!empty($content)) {

            $namespace = str_replace('\\Plugin', '\\', $content['plugin']);
            $composer = get_file_data(base_path() . '/composer.json');

            if (!empty($composer) && !isset($composer['autoload']['psr-4'][$namespace])) {
                $composer['autoload']['psr-4'][$namespace] = 'plugins/' . strtolower($plugin_folder) . '/src';
                save_file_data(base_path() . '/composer.json', $composer);
                Artisan::call('dump-autoload');
            }

            $plugin = app(PluginInterface::class)->getFirstBy(['provider' => $content['provider']]);
            if (empty($plugin) || $plugin->status == 1) {
                call_user_func([$content['plugin'], 'deactivate']);
                if (empty($plugin)) {
                    $plugin = app(PluginInterface::class)->getModel();
                    $plugin->fill($content);
                }
                $plugin->alias = strtolower($plugin_folder);
                $plugin->status = 0;
                app(PluginInterface::class)->createOrUpdate($plugin);
                cache()->forget(md5('cache-dashboard-menu'));
                $this->line('<info>Deactivate plugin successfully!</info>');
            } else {
                $this->line('<info>This plugin is deactivated already!</info>');
            }
        }

        if (!$this->files->isDirectory($location)) {
            $this->error('This plugin is not exists.');
            return false;
        }
        return true;
    }
}
