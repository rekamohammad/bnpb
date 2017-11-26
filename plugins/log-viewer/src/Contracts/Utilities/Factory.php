<?php

namespace Botble\LogViewer\Contracts\Utilities;

use Botble\LogViewer\Contracts\Patternable;

interface Factory extends Patternable
{
    /**
     * Get the filesystem instance.
     *
     * @return \Botble\LogViewer\Contracts\Utilities\Filesystem
     * @author ARCANEDEV <arcanedev.maroc@gmail.com>
     */
    public function getFilesystem();

    /**
     * Set the filesystem instance.
     *
     * @param  \Botble\LogViewer\Contracts\Utilities\Filesystem $filesystem
     *
     * @return self
     * @author ARCANEDEV <arcanedev.maroc@gmail.com>
     */
    public function setFilesystem(Filesystem $filesystem);

    /**
     * Get the log levels instance.
     *
     * @return  \Botble\LogViewer\Contracts\Utilities\LogLevels  $levels
     * @author ARCANEDEV <arcanedev.maroc@gmail.com>
     */
    public function getLevels();

    /**
     * Set the log levels instance.
     *
     * @param  \Botble\LogViewer\Contracts\Utilities\LogLevels $levels
     *
     * @return self
     * @author ARCANEDEV <arcanedev.maroc@gmail.com>
     */
    public function setLevels(LogLevels $levels);

    /**
     * Set the log storage path.
     *
     * @param  string $storagePath
     *
     * @return self
     * @author ARCANEDEV <arcanedev.maroc@gmail.com>
     */
    public function setPath($storagePath);

    /**
     * Get all logs.
     *
     * @return \Botble\LogViewer\Entities\LogCollection
     * @author ARCANEDEV <arcanedev.maroc@gmail.com>
     */
    public function logs();

    /**
     * Get all logs (alias).
     *
     * @return \Botble\LogViewer\Entities\LogCollection
     * @author ARCANEDEV <arcanedev.maroc@gmail.com>
     */
    public function all();

    /**
     * Paginate all logs.
     *
     * @param  int $perPage
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator
     * @author ARCANEDEV <arcanedev.maroc@gmail.com>
     */
    public function paginate($perPage = 30);

    /**
     * Get a log by date.
     *
     * @param  string $date
     *
     * @return \Botble\LogViewer\Entities\Log
     * @author ARCANEDEV <arcanedev.maroc@gmail.com>
     */
    public function log($date);

    /**
     * Get a log by date (alias).
     *
     * @param  string $date
     *
     * @return \Botble\LogViewer\Entities\Log
     * @author ARCANEDEV <arcanedev.maroc@gmail.com>
     */
    public function get($date);

    /**
     * Get log entries.
     *
     * @param  string $date
     * @param  string $level
     *
     * @return \Botble\LogViewer\Entities\LogEntryCollection
     * @author ARCANEDEV <arcanedev.maroc@gmail.com>
     */
    public function entries($date, $level = 'all');

    /**
     * List the log files (dates).
     *
     * @return array
     * @author ARCANEDEV <arcanedev.maroc@gmail.com>
     */
    public function dates();

    /**
     * Get logs count.
     *
     * @return int
     * @author ARCANEDEV <arcanedev.maroc@gmail.com>
     */
    public function count();

    /**
     * Get total log entries.
     *
     * @param  string $level
     *
     * @return int
     * @author ARCANEDEV <arcanedev.maroc@gmail.com>
     */
    public function total($level = 'all');

    /**
     * Get tree menu.
     *
     * @param  bool $trans
     *
     * @return array
     * @author ARCANEDEV <arcanedev.maroc@gmail.com>
     */
    public function tree($trans = false);

    /**
     * Get tree menu.
     *
     * @param  bool $trans
     *
     * @return array
     * @author ARCANEDEV <arcanedev.maroc@gmail.com>
     */
    public function menu($trans = true);

    /**
     * Get logs statistics.
     *
     * @return array
     * @author ARCANEDEV <arcanedev.maroc@gmail.com>
     */
    public function stats();

    /**
     * Get logs statistics table.
     *
     * @param  string|null $locale
     *
     * @return \Botble\LogViewer\Tables\StatsTable
     * @author ARCANEDEV <arcanedev.maroc@gmail.com>
     */
    public function statsTable($locale = null);

    /**
     * Determine if the log folder is empty or not.
     *
     * @return bool
     * @author ARCANEDEV <arcanedev.maroc@gmail.com>
     */
    public function isEmpty();
}
