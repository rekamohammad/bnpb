<?php

namespace Botble\Translation\Console;

use Botble\Translation\Manager;
use Illuminate\Console\Command;
use Illuminate\Console\OutputStyle;
use Symfony\Component\Finder\Finder;

class FindCommand extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'translations:find';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Find translations in php/twig files';

    /**
     * @var Manager
     */
    protected $manager;

    /**
     * FindCommand constructor.
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
     * @return int
     * @author Barry vd. Heuvel <barryvdh@gmail.com>
     */
    public function fire()
    {
        $this->info('Starting find translation....');
        $counter = $this->findTranslations(base_path('plugins'));
        $this->info('Done importing, processed ' . $counter . ' items!');
        return $counter;
    }

    /**
     * @param null $path
     * @return mixed
     * @author Barry vd. Heuvel <barryvdh@gmail.com>
     */
    public function findTranslations($path = null)
    {
        $path = $path ?: base_path();
        // Find all PHP + Twig files in the app folder, except for storage
        $finder = new Finder();
        $finder->in($path)->exclude('storage')->name('*.php')->name('*.twig')->files();

        $keys = [];

        $functions = ['trans', 'trans_choice', 'Lang::get', 'Lang::choice', 'Lang::trans', 'Lang::transChoice', '@lang', '@choice', '__'];
        $pattern =                                         // See http://regexr.com/392hu
            '[^\w|>]' .                                   // Must not have an alpha-num or _ or > before real method
            '(' . implode('|', $functions) . ')' .       // Must start with one of the functions
            '\(' .                                      // Match opening paren these
            '[\'\']' .                                 // Match ' or '
            '(' .                                     // Start a new group to match:
            '[a-zA-Z0-9_-]+' .                       // Must start with group
            '([.][^\1)]+)+' .                       // Be followed by one or more items/keys
            ')' .                                  // Close group
            '[\'\']' .                            // Closing quote
            '[\),]';                             // Close parentheses or new parameter


        /**
         * @var Command $this
         * @var OutputStyle $output
         * @var Finder $finder
         */
        $output = $this->getOutput();

        $bar = $output->createProgressBar($finder->count());
        $this->line('');
        $this->info('Translation - Preparing to search in ' . $finder->count() . ' files');
        $this->line('');
        foreach ($finder as $file) {
            if (preg_match_all('/' . $pattern . '/siU', $file->getContents(), $matches)) {
                foreach ($matches[2] as $key) {
                    $keys[] = $key;
                }
            }
            $bar->advance();
        }
        $bar->finish();
        $keys = array_unique($keys);

        // Add the translations to the database, if not existing.
        foreach ($keys as $key) {
            // Split the group and item
            list($group, $item) = explode('.', $key, 2);
            $this->manager->missingKey($group, $item);
        }

        $this->line('');
        $this->line('');
        $this->info('Found ' . count($keys) . ' sentences');
        return count($keys);
    }
}
