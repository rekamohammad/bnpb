<?php

namespace Botble\Setting;

use Botble\Setting\Supports\ArrayUtil;
use Exception;
use Form;
use Illuminate\Database\Connection;

class Setting
{
    /**
     * The settings data.
     *
     * @var array
     */
    protected $data = [];

    /**
     * Whether the store has changed since it was last loaded.
     *
     * @var boolean
     */
    protected $unsaved = false;

    /**
     * Whether the settings data are loaded.
     *
     * @var boolean
     */
    protected $loaded = false;

    /**
     * The database connection instance.
     *
     * @var \Illuminate\Database\Connection
     */
    protected $connection;

    /**
     * The table to query from.
     *
     * @var string
     */
    protected $table = 'settings';

    /**
     * Any extra columns that should be added to the rows.
     *
     * @var array
     */
    protected $extraColumns = [];

    /**
     * @param \Illuminate\Database\Connection $connection
     * @author Andreas Lutro <anlutro@gmail.com>
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * Get a specific key from the settings data.
     *
     * @param  string|array $key
     * @param  mixed $default Optional default value.
     *
     * @return mixed
     * @author Andreas Lutro <anlutro@gmail.com>
     */
    public function get($key, $default = null)
    {

        try {
            $this->checkLoaded();

            return ArrayUtil::get($this->data, $key, $default);
        } catch (Exception $ex) {
            return null;
        }
    }

    /**
     * Determine if a key exists in the settings data.
     *
     * @param  string $key
     *
     * @return boolean
     * @author Andreas Lutro <anlutro@gmail.com>
     */
    public function has($key)
    {
        $this->checkLoaded();

        return ArrayUtil::has($this->data, $key);
    }

    /**
     * Set a specific key to a value in the settings data.
     *
     * @param string|array $key Key string or associative array of key => value
     * @param mixed $value Optional only if the first argument is an array
     * @author Andreas Lutro <anlutro@gmail.com>
     */
    public function set($key, $value = null)
    {
        $this->checkLoaded();
        $this->unsaved = true;

        if (is_array($key)) {
            foreach ($key as $k => $v) {
                ArrayUtil::set($this->data, $k, $v);
            }
        } else {
            ArrayUtil::set($this->data, $key, $value);
        }
    }

    /**
     * Unset all keys in the settings data.
     *
     * @return void
     * @author Andreas Lutro <anlutro@gmail.com>
     */
    public function forgetAll()
    {
        $this->unsaved = true;
        $this->data = [];
    }

    /**
     * Get all settings data.
     *
     * @return array
     * @author Andreas Lutro <anlutro@gmail.com>
     */
    public function all()
    {
        $this->checkLoaded();

        return $this->data;
    }

    /**
     * Save any changes done to the settings data.
     *
     * @return void
     * @author Andreas Lutro <anlutro@gmail.com>
     */
    public function save()
    {
        if (!$this->unsaved) {
            // either nothing has been changed, or data has not been loaded, so
            // do nothing by returning early
            return;
        }

        $this->write($this->data);
        $this->unsaved = false;
    }

    /**
     * Check if the settings data has been loaded.
     * @author Andreas Lutro <anlutro@gmail.com>
     */
    protected function checkLoaded()
    {
        if (!$this->loaded) {
            $this->data = $this->read();
            $this->loaded = true;
        }
    }

    /**
     * Set extra columns to be added to the rows.
     *
     * @param array $columns
     * @author Andreas Lutro <anlutro@gmail.com>
     */
    public function setExtraColumns(array $columns)
    {
        $this->extraColumns = $columns;
    }

    /**
     * @param $key
     * @author Andreas Lutro <anlutro@gmail.com>
     */
    public function forget($key)
    {
        $this->unsaved = true;

        if ($this->has($key)) {
            ArrayUtil::forget($this->data, $key);
        }

        // because the database store cannot store empty arrays, remove empty
        // arrays to keep data consistent before and after saving
        $segments = explode('.', $key);
        array_pop($segments);

        while ($segments) {
            $segment = implode('.', $segments);

            // non-empty array - exit out of the loop
            if ($this->get($segment)) {
                break;
            }

            // remove the empty array and move on to the next segment
            $this->forget($segment);
            array_pop($segments);
        }
    }

    /**
     * @param array $data
     * @author Andreas Lutro <anlutro@gmail.com>
     */
    protected function write(array $data)
    {
        $keys = $this->newQuery()->pluck('key');

        $insertData = array_dot($data);
        $updateData = [];
        $deleteKeys = [];

        foreach ($keys as $key) {
            if (isset($insertData[$key])) {
                $updateData[$key] = $insertData[$key];
            } else {
                $deleteKeys[] = $key;
            }
            unset($insertData[$key]);
        }

        foreach ($updateData as $key => $value) {
            $this->newQuery()
                ->where('key', '=', $key)
                ->update(['value' => $value]);
        }

        if ($insertData) {
            $this->newQuery(true)
                ->insert($this->prepareInsertData($insertData));
        }

        if ($deleteKeys) {
            $this->newQuery()
                ->whereIn('key', $deleteKeys)
                ->delete();
        }
    }

    /**
     * Transforms settings data into an array ready to be inserted into the
     * database. Call array_dot on a multidimensional array before passing it
     * into this method!
     *
     * @param  array $data Call array_dot on a multidimensional array before passing it into this method!
     *
     * @return array
     * @author Andreas Lutro <anlutro@gmail.com>
     */
    protected function prepareInsertData(array $data)
    {
        $dbData = [];

        if ($this->extraColumns) {
            foreach ($data as $key => $value) {
                $dbData[] = array_merge(
                    $this->extraColumns,
                    ['key' => $key, 'value' => $value]
                );
            }
        } else {
            foreach ($data as $key => $value) {
                $dbData[] = ['key' => $key, 'value' => $value];
            }
        }

        return $dbData;
    }

    /**
     * @return array
     * @author Andreas Lutro <anlutro@gmail.com>
     */
    protected function read()
    {
        return $this->parseReadData($this->newQuery()->get());
    }

    /**
     * @param $data
     * @return array
     * Parse data coming from the database.
     * @author Andreas Lutro <anlutro@gmail.com>
     */
    public function parseReadData($data)
    {
        $results = [];

        foreach ($data as $row) {
            if (is_array($row)) {
                $key = $row['key'];
                $value = $row['value'];
            } elseif (is_object($row)) {
                $key = $row->key;
                $value = $row->value;
            } else {
                $msg = 'Expected array or object, got ' . gettype($row);
                throw new \UnexpectedValueException($msg);
            }

            ArrayUtil::set($results, $key, $value);
        }

        return $results;
    }

    /**
     * Create a new query builder instance.
     *
     * @param  $insert  boolean  Whether the query is an insert or not.
     *
     * @return \Illuminate\Database\Query\Builder
     * @author Andreas Lutro <anlutro@gmail.com>
     */
    protected function newQuery($insert = false)
    {
        $query = $this->connection->table($this->table);

        if (!$insert) {
            foreach ($this->extraColumns as $key => $value) {
                $query->where($key, '=', $value);
            }
        }

        return $query;
    }

    /**
     * @param $setting
     * @return mixed|string
     * @author Sang Nguyen
     */
    public function render($setting)
    {
        try {
            if ($this->has($setting['attributes']['name'])) {
                $setting['attributes']['value'] = $this->get($setting['attributes']['name']);
            }

            return call_user_func_array([Form::class, $setting['type']], $setting['attributes']);
        } catch(Exception $ex) {
            return 'This field type does not exist';
        }
    }

    /**
     * @return array
     * @author Sang Nguyen
     */
    public static function getConfig()
    {
        return require __DIR__ . '/../config/global.php';
    }
}
