<?php

namespace Botble\Base\Commands;

use Artisan;
use Botble\ACL\Repositories\Interfaces\UserInterface;
use Exception;
use File;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use DB;
use Symfony\Component\Console\Helper\SymfonyQuestionHelper;
use Symfony\Component\Console\Question\Question;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cms:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Installation of BNPB CMS: Laravel setup, installation of npm packages';

    /**
     * The filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;

    /**
     * @var UserInterface
     */
    protected $userRepository;

    /**
     * @var string
     */
    protected $username;

    /**
     * @var string
     */
    protected $database;

    /**
     * @var string
     */
    protected $password;

    /**
     * Install constructor.
     * @param UserInterface $user
     * @param Filesystem $files
     * @author Sang Nguyen
     */
    public function __construct(UserInterface $user, Filesystem $files)
    {
        parent::__construct();

        $this->userRepository = $user;
        $this->files = $files;
    }

    /**
     * Execute the console command.
     *
     * @author Sang Nguyen
     */
    public function handle()
    {
        $this->line('------------------');
        $this->line('Welcome to BNPB CMS');
        $this->line('------------------');

        $extensions = get_loaded_extensions();
        $require_extensions = ['mbstring', 'openssl', 'curl', 'exif', 'fileinfo', 'tokenizer'];
        foreach (array_diff($require_extensions, $extensions) as $missing_extension) {
            $this->error('Missing ' . ucfirst($missing_extension) . ' extension');
        }

        if (!file_exists('.env')) {
            File::copy('.env-example', '.env');
        }

        // Set database credentials in .env and migrate
        $this->setDatabaseInfo();
        $this->line('------------------');

        Artisan::call('key:generate');

        // Set cache key prefix
        $this->setCacheKeyPrefix($this->database);
        $this->line('------------------');

        // Create a super user
        $this->createSuperUser();

        if (function_exists('system')) {
            $this->info('Running npm install...');
            system('npm install');
            $this->info('npm packages installed.');

            if (!windows_os()) {
                $this->info('Reset chmod files and folders.');
                system('sudo find * -type d -exec chmod 755 {} \;');
                system('sudo find * -type f -exec chmod 644 {} \;');

                system('sudo find storage -type d -exec chmod 777 {} \;');
                $this->info('Directory storage is now writable (777).');
                system('sudo find bootstrap/cache -type d -exec chmod 777 {} \;');
                $this->info('Directory bootstrap/cache is now writable (777).');
                system('sudo find public/uploads -type d -exec chmod 777 {} \;');
                $this->info('Directory public/uploads is now writable (777).');
                $this->line('------------------');
            }
        } else {
            $this->line('You can now make /storage, /bootstrap/cache and /public/uploads directories writable and run npm install.');
        }

        // Done
        $this->line('------------------');
        $this->line('Done. Enjoy BNPB CMS!');
    }

    /**
     * @param $prefix
     * @return void
     * @author Sang Nguyen
     */
    protected function setCacheKeyPrefix($prefix)
    {
        $path = 'config/cache.php';
        list($path, $contents) = [$path, $this->files->get($path)];

        $contents = str_replace($this->laravel['config']['cache.prefix'], $prefix, $contents);

        $this->files->put($path, $contents);

        $this->laravel['config']['cache.prefix'] = $prefix;

        $this->info('Application cache key prefix ' . $prefix . ' set successfully.');
    }

    /**
     * @throws Exception
     * @return void
     * @author Sang Nguyen
     */
    protected function setDatabaseInfo()
    {
        $this->info('Setting up database (please make sure you created database for this site)...');

        $this->database = env('DB_DATABASE');
        $this->username = env('DB_USERNAME');
        $this->password = env('DB_PASSWORD');

        while (!check_database_connection()) {
            // Ask for database name
            $this->database = $this->ask('Enter a database name', $this->guessDatabaseName());

            $this->username = $this->ask('What is your MySQL username?', 'root');

            $question = new Question('What is your MySQL password?', '<none>');
            $question->setHidden(true)->setHiddenFallback(true);
            $this->password = (new SymfonyQuestionHelper())->ask($this->input, $this->output, $question);
            if ($this->password === '<none>') {
                $this->password = '';
            }

            // Update DB credentials in .env file.
            $contents = $this->getKeyFile();
            $contents = preg_replace('/(' . preg_quote('DB_DATABASE=') . ')(.*)/', 'DB_DATABASE=' . $this->database, $contents);
            $contents = preg_replace('/(' . preg_quote('DB_USERNAME=') . ')(.*)/', 'DB_USERNAME=' . $this->username, $contents);
            $contents = preg_replace('/(' . preg_quote('DB_PASSWORD=') . ')(.*)/', 'DB_PASSWORD=' . $this->password, $contents);

            if (!$contents) {
                throw new Exception('Error while writing credentials to .env file.');
            }

            // Write to .env
            $this->files->put('.env', $contents);

            // Set DB username and password in config
            $this->laravel['config']['database.connections.mysql.username'] = $this->username;
            $this->laravel['config']['database.connections.mysql.password'] = $this->password;

            // Clear DB name in config
            unset($this->laravel['config']['database.connections.mysql.database']);

            if (!check_database_connection()) {
                $this->error('Can not connect to database, please try again!');
            } else {
                $this->info('Connect to database successfully!');
            }
        }

        if (!empty($this->database)) {
            // Force the new login to be used
            DB::purge();

            // Switch to use {$this->database}
            DB::unprepared('USE `' . $this->database . '`');
            DB::connection()->setDatabaseName($this->database);

            $this->info('Import default database...');

            DB::unprepared(file_get_contents(base_path() . '/database/dump/base.sql'));
        }
    }

    /**
     * Guess database name from app folder.
     *
     * @return string
     * @author Sang Nguyen
     */
    protected function guessDatabaseName()
    {
        try {
            $segments = array_reverse(explode(DIRECTORY_SEPARATOR, app_path()));
            $name = explode('.', $segments[1])[0];

            return str_slug($name);
        } catch (Exception $e) {
            return '';
        }
    }

    /**
     * Get the key file and return its content.
     *
     * @return string
     * @author Sang Nguyen
     */
    protected function getKeyFile()
    {
        return $this->files->exists('.env') ? $this->files->get('.env') : $this->files->get('.env.example');
    }

    /**
     * Create a superuser.
     *
     * @return void
     * @author Sang Nguyen
     */
    protected function createSuperUser()
    {
        $this->info('Creating a Super User...');

        $user = $this->userRepository->getModel();
        $user->first_name = $this->ask('Enter your first name');
        $user->last_name = $this->ask('Enter your last name');
        $user->username = $this->ask('Enter your username');
        $user->email = $this->ask('Enter your email address');
        $user->super_user = 1;
        $user->manage_supers = 1;
        $user->password = bcrypt($this->secret('Enter a password'));
        $user->profile_image = config('acl.avatar.default');

        try {
            $this->userRepository->createOrUpdate($user);
            if (acl_activate_user($user)) {
                $this->info('Super user is created.');
            }
        } catch (Exception $e) {
            $this->error('User could not be created.');
            $this->error($e->getMessage());
        }

        $this->line('------------------');
    }
}
