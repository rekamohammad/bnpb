<?php

namespace Botble\Base\Commands;

use Illuminate\Console\Command;

class PluginListCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'plugin:list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Show all plugins information';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $modules = get_all_plugins();

        $header = ['Name', 'Alias', 'Version', 'Provider', 'Status', 'Author'];
        $result = [];
        foreach ($modules as $module) {
            $result[] = [
                array_get($module, 'name'),
                array_get($module, 'alias'),
                array_get($module, 'version'),
                array_get($module, 'provider'),
                array_get($module, 'status') ? '✓ activated' : '✘ deactivated',
                array_get($module, 'author'),
            ];
        }

        $this->table($header, $result);
    }
}