<?php

namespace Botble\Base\Commands;

use Artisan;
use Botble\Page\Models\Page;
use Illuminate\Console\Command;

class CreateFreshDatabaseCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cms:fresh_database';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear all dummy data for fresh site';

    /**
     * CreateFreshDatabaseCommand constructor.
     * @author Sang Nguyen
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     * @author Sang Nguyen
     */
    public function handle()
    {
        if ($this->confirm('Are you sure you want to do this action, the data of some tables will be empty? [yes|no]')) {
            Page::truncate();

            Artisan::call('cache:clear');
            Artisan::call('view:clear');
            Artisan::call('clear-compile');

            $this->info('Clear dummy data successfully!');
        } else {
            $this->info('Abort!');
        }
    }
}
