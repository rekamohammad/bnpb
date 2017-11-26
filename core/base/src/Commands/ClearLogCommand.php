<?php

namespace Botble\Base\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class ClearLogCommand extends Command
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
    protected $signature = 'log:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear log files';

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
     * Execute the console command.
     * @author Sang Nguyen
     */
    public function fire()
    {
        $files = scan_folder(storage_path('logs'));
        foreach ($files as $file) {
            if (!in_array($file, ['.gitignore'])) {
                $this->files->delete(storage_path('logs') . DIRECTORY_SEPARATOR . $file);
            }
        }
        $this->info('Clear log files successfully!');
    }
}
