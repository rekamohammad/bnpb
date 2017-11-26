<?php

namespace Botble\LogViewer\Contracts;

use Botble\LogViewer\Entities\LogEntryCollection;
use Botble\LogViewer\Entities\Log;
use Botble\LogViewer\Entities\LogCollection;
use Botble\LogViewer\Exceptions\FilesystemException;
use Botble\LogViewer\Tables\StatsTable;

interface LogViewer extends Patternable
{
    /**
     * Get the log levels.
     *
     * @param  bool|false $flip
     *
     * @return array
     * @author ARCANEDEV <arcanedev.maroc@gmail.com>
     */
    public function levels($flip = false);

    /**
     * Get the translated log levels.
     *
     * @param  string|null $locale
     *
     * @return array
     * @author ARCANEDEV <arcanedev.maroc@gmail.com>
     */
    public function levelsNames($locale = null);

    /**
     * Set the log storage path.
     *
     * @param  string $path
     *
     * @return \Botble\LogViewer\LogViewer
     * @author ARCANEDEV <arcanedev.maroc@gmail.com>
     */
    public function setPath($path);

    /**
     * Get all logs.
     *
     * @return LogCollection
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
     * Get a log.
     *
     * @param  string $date
     *
     * @return Log
     * @author ARCANEDEV <arcanedev.maroc@gmail.com>
     */
    public function get($date);

    /**
     * Get the log entries.
     *
     * @param  string $date
     * @param  string $level
     *
     * @return LogEntryCollection
     * @author ARCANEDEV <arcanedev.maroc@gmail.com>
     */
    public function entries($date, $level = 'all');

    /**
     * Download a log file.
     *
     * @param  string $date
     * @param  string|null $filename
     * @param  array $headers
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     * @author ARCANEDEV <arcanedev.maroc@gmail.com>
     */
    public function download($date, $filename = null, $headers = []);

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
     * @return StatsTable
     * @author ARCANEDEV <arcanedev.maroc@gmail.com>
     */
    public function statsTable($locale = null);

    /**
     * Delete the log.
     *
     * @param  string $date
     *
     * @return bool
     *
     * @throws FilesystemException
     * @author ARCANEDEV <arcanedev.maroc@gmail.com>
     */
    public function delete($date);

    /**
     * List the log files.
     *
     * @return array
     * @author ARCANEDEV <arcanedev.maroc@gmail.com>
     */
    public function files();

    /**
     * List the log files (only dates).
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
     * Get entries total from all logs.
     *
     * @param  string $level
     *
     * @return int
     * @author ARCANEDEV <arcanedev.maroc@gmail.com>
     */
    public function total($level = 'all');

    /**
     * Get logs tree.
     *
     * @param  bool|false $trans
     *
     * @return array
     * @author ARCANEDEV <arcanedev.maroc@gmail.com>
     */
    public function tree($trans = false);

    /**
     * Get logs menu.
     *
     * @param  bool|true $trans
     *
     * @return array
     * @author ARCANEDEV <arcanedev.maroc@gmail.com>
     */
    public function menu($trans = true);

    /**
     * Determine if the log folder is empty or not.
     *
     * @return bool
     * @author ARCANEDEV <arcanedev.maroc@gmail.com>
     */
    public function isEmpty();
}
