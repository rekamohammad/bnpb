<?php

namespace Botble\Translation\Console;

use Botble\Translation\Manager;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;

class ExportCommand extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'translations:export';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Export translations to PHP files';

    /**
     * @var Manager
     */
    protected $manager;

    /**
     * ExportCommand constructor.
     * @param Manager $manager
     * @author Barry vd. Heuvel <barryvdh@gmail.com>
     */
    public function __construct(Manager $manager)
    {
        $this->manager = $manager;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     * @author Barry vd. Heuvel <barryvdh@gmail.com>
     */
    public function fire()
    {
        $group = $this->argument('group');

        $this->manager->exportTranslations($group);

        $this->info('Done writing language files for ' . (($group == '*') ? 'ALL groups' : $group . ' group'));

    }

    /**
     * Get the console command arguments.
     *
     * @return array
     * @author Barry vd. Heuvel <barryvdh@gmail.com>
     */
    protected function getArguments()
    {
        return [
            ['group', InputArgument::REQUIRED, 'The group to export (`*` for all).'],
        ];
    }
}
