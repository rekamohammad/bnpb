<?php

namespace Botble\Base\Commands;

use Artisan;
use Carbon;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Composer;
use League\Flysystem\Adapter\Local as LocalAdapter;
use League\Flysystem\Filesystem as Flysystem;
use League\Flysystem\MountManager;

class PluginCreateCommand extends Command
{
    /**
     * The filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;

    /**
     * The name of the module uppercase first character.
     *
     * @var string
     */
    protected $plugin;

    /**
     * @var string
     */
    protected $location;

    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'plugin:create {name : The module that you want to create} {--force : Overwrite any existing files.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a plugin in the /plugins directory.';

    /**
     * @var Composer
     */
    protected $composer;

    /**
     * Create a new key generator command.
     *
     * @param \Illuminate\Filesystem\Filesystem $files
     * @param Composer $composer
     * @author Sang Nguyen
     */
    public function __construct(Filesystem $files, Composer $composer)
    {
        parent::__construct();

        $this->files = $files;
        $this->composer = $composer;
    }

    /**
     * Execute the console command.
     * @author Sang Nguyen
     */
    public function fire()
    {
        if (!preg_match('/^[a-z\-]+$/i', $this->argument('name'))) {
            $this->error('Only alphabetic characters are allowed.');
            return false;
        }

        $this->plugin = ucfirst(strtolower($this->argument('name')));
        $this->location = config('cms.plugin_path') . '/' . strtolower($this->plugin);

        if ($this->files->isDirectory($this->location)) {
            $this->error('A plugin named [' . $this->plugin . '] already exists.');
            return false;
        }

        $this->publishStubs();
        $this->renameModelsAndRepositories($this->location);
        $this->searchAndReplaceInFiles();
        $this->line('------------------');
        $this->line('<info>The plugin</info> <comment>' . studly_case($this->plugin) . '</comment> <info>was created in</info> <comment>' . $this->location . '</comment><info>, customize it!</info>');
        $this->line('------------------');

        $plugin = get_file_data($this->location . '/plugin.json');
        if (!empty($plugin)) {
            $composer = get_file_data(base_path() . '/composer.json');
            if (!empty($composer)) {
                $composer['autoload']['psr-4']['Botble\\' . studly_case($this->plugin) . '\\'] = 'plugins/' . strtolower($this->plugin) . '/src';
                save_file_data(base_path() . '/composer.json', $composer);
            }

            $this->composer->dumpAutoloads();
            $this->line('Composer autoload refreshed!');
            Artisan::call('cache:clear');
        }

        $this->call('optimize');
        return true;
    }

    /**
     * Generate the module in Modules directory.
     * @author Sang Nguyen
     */
    protected function publishStubs()
    {
        $from = base_path('core/base/stubs/plugin');

        if ($this->files->isDirectory($from)) {
            $this->publishDirectory($from, $this->location);
        } else {
            $this->error('Can’t locate path: <' . $from . '>');
        }
    }

    /**
     * Search and replace all occurrences of ‘Module’
     * in all files with the name of the new module.
     * @author Sang Nguyen
     */
    public function searchAndReplaceInFiles()
    {

        $manager = new MountManager([
            'directory' => new Flysystem(new LocalAdapter($this->location)),
        ]);

        foreach ($manager->listContents('directory://', true) as $file) {
            if ($file['type'] === 'file') {
                $content = str_replace(['{plugin}', '{Plugin}', '{PLUGIN}', '{migrate_date}'], [strtolower($this->plugin), studly_case($this->plugin), strtoupper($this->plugin), Carbon::now()->format('Y_m_d_His')], $manager->read('directory://' . $file['path']));
                $manager->put('directory://' . $file['path'], $content);
            }
        }
    }

    /**
     * Rename models and repositories.
     * @param $location
     * @return boolean
     * @author Sang Nguyen
     */
    public function renameModelsAndRepositories($location)
    {
        $paths = scan_folder($location);
        if (empty($paths)) {
            return false;
        }
        foreach ($paths as $path) {
            $path = $location . DIRECTORY_SEPARATOR . $path;

            $newPath = $this->transformFilename($path);
            rename($path, $newPath);

            $this->renameModelsAndRepositories($newPath);
        }
        return true;
    }

    /**
     * Rename file in path.
     *
     * @param string $path
     * @return string
     * @author Sang Nguyen
     */
    public function transformFilename($path)
    {
        return str_replace(
            ['{plugin}', '{Plugin}', '.stub', '{migrate_date}'],
            [strtolower($this->plugin), studly_case($this->plugin), '.php', Carbon::now()->format('Y_m_d_His')],
            $path
        );
    }

    /**
     * Publish the directory to the given directory.
     *
     * @param string $from
     * @param string $to
     * @return void
     * @author Sang Nguyen
     */
    protected function publishDirectory($from, $to)
    {
        $manager = new MountManager([
            'from' => new Flysystem(new LocalAdapter($from)),
            'to' => new Flysystem(new LocalAdapter($to)),
        ]);

        foreach ($manager->listContents('from://', true) as $file) {
            if ($file['type'] === 'file' && (!$manager->has('to://' . $file['path']) || $this->option('force'))) {
                $manager->put('to://' . $file['path'], $manager->read('from://' . $file['path']));
            }
        }
    }

    /**
     * Create the directory to house the published files if needed.
     *
     * @param string $directory
     * @return void
     * @author Sang Nguyen
     */
    protected function createParentDirectory($directory)
    {
        if (!$this->files->isDirectory($directory)) {
            $this->files->makeDirectory($directory, 0755, true);
        }
    }
}
