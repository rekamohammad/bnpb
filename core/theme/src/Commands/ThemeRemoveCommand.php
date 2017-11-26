<?php

namespace Botble\Theme\Commands;

use Illuminate\Console\Command;
use Illuminate\Config\Repository;
use Illuminate\Filesystem\Filesystem as File;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ThemeRemoveCommand extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'theme:remove';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove theme existing';

    /**
     * @var Repository
     */
    protected $config;

    /**
     * @var File
     */
    protected $files;

    /**
     * Create a new command instance.
     *
     * @param \Illuminate\Config\Repository $config
     * @param \Illuminate\Filesystem\Filesystem $files
     * @author Teepluss <admin@laravel.in.th>
     */
    public function __construct(Repository $config, File $files)
    {
        $this->config = $config;

        $this->files = $files;

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     * @author Teepluss <admin@laravel.in.th>
     */
    public function fire()
    {
        // The theme is not exists.
        if (!$this->files->isDirectory($this->getPath(null))) {
            $this->error('Theme "' . $this->getTheme() . '" is not exists.');
            return false;
        }

        $themePath = $this->getPath(null);

        if ($this->confirm('Are you sure you want to permanently delete? [yes|no]')) {
            // Delete permanent.
            $this->files->deleteDirectory($themePath, false);

            $this->info('Theme "' . $this->getTheme() . '" has been destroyed.');
        }
        return true;
    }

    /**
     * Get root writable path.
     *
     * @param  string $path
     * @return string
     * @author Teepluss <admin@laravel.in.th>
     */
    protected function getPath($path)
    {
        $rootPath = $this->option('path');

        return $rootPath . '/' . strtolower($this->getTheme()) . '/' . $path;
    }

    /**
     * Get the theme name.
     *
     * @return string
     * @author Teepluss <admin@laravel.in.th>
     */
    protected function getTheme()
    {
        return strtolower($this->argument('name'));
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     * @author Teepluss <admin@laravel.in.th>
     */
    protected function getArguments()
    {
        return [
            [
                'name',
                InputArgument::REQUIRED,
                'Name of the theme to generate.',
            ],
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     * @author Teepluss <admin@laravel.in.th>
     */
    protected function getOptions()
    {
        $path = public_path() . '/' . $this->config->get('theme.themeDir');

        return [
            [
                'path',
                null,
                InputOption::VALUE_OPTIONAL,
                'Path to theme directory.', $path,
            ],
        ];
    }
}
