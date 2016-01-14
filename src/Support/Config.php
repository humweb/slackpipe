<?php

namespace Humweb\SlackPipe\Support;

use Humweb\SlackPipe\Support\Contracts\ConfigInterface;

/**
 * JiraConfig
 *
 * @package Humweb\SlackPipe\Providers\Jira
 */
class Config implements ConfigInterface
{

    protected $filename;
    protected $storage;
    protected $data      = [];


    protected $accessors = [];
    protected $mutators  = [];

    /**
     * JiraConfig constructor.
     *
     * @param string $filename
     * @param bool   $eager
     */
    public function __construct($filename = null, $eager = false)
    {
        if ( ! is_null($filename)) {
            $this->filename = $filename;
        }

        $this->storage  = Container::getInstance()->get('storage');

        // Eager load data
        if ($eager === true) {
            $this->read();
        }
    }

    static public function factory($filename, $eager = false)
    {
        if ($config = Asserts::hasCustomConfig($filename)) {
            return new $config($filename, $eager);
        }

        return new static($filename, $eager);
    }

    /**
     * @return array
     */
    public function callAccessors($field, $value)
    {
        return $this->accessors[$field]($value);
    }

    /**
     * @param          $field
     * @param callable $accessors
     */
    public function addAccessor($field, $accessors)
    {
        $this->accessors[$field] = $accessors;
    }

    /**
     * @param        $field
     * @param string $value
     *
     * @return mixed
     */
    public function callMutators($field, $value = '')
    {
        return $this->mutators[$field]($value);
    }

    /**
     * @param          $field
     * @param callable $mutators
     */
    public function addMutators($field, $mutators)
    {
        $this->mutators[$field] = $mutators;
    }

    /**
     * Write data to config file
     *
     * @param array $data
     *
     * @return bool
     */
    public function write($data = [])
    {
        $this->data = $data;
        $data       = $this->dehydrate($data);

        return $this->storage->put($this->file(), "<?php\nreturn ".var_export($data, true).";");
    }

    /**
     * Dehydrate data for storage
     *
     * @param array $data
     *
     * @return array
     */
    public function dehydrate($data = [])
    {
        if (empty($this->mutators)) {
            return $data;
        }

        foreach ($this->mutators as $field => $func) {
            if (array_key_exists($field, $data)) {
                $data[$field] = $func($data[$field]);
            }
        }

        return $data;
    }

    /**
     * @return string
     */
    public function file()
    {
        return $this->filename.'.php';
    }

    /**
     * @return array
     */
    public function read()
    {
        if (empty($this->data)) {
            $this->data = $this->hydrate(include($this->filePath()));
        }

        return $this->data;
    }

    /**
     * Hydrate data from storage
     *
     * @param array $data
     *
     * @return array
     */
    public function hydrate($data = [])
    {
        if (empty($this->accessors)) {
            return $data;
        }

        foreach ($this->accessors as $field => $func) {
            if (array_key_exists($field, $data)) {
                $data[$field] = $func($data[$field]);
            }
        }

        return $data;
    }

    /**
     * @return string
     */
    public function filePath()
    {
        return $this->storage->path($this->filename.'.php');
    }

    /**
     * @return bool
     */
    public function exists()
    {
        return file_exists($this->filePath());
    }


    // Data Access
    public function get($key, $default = null)
    {
        return $this->has($key) ? $this->data[$key] : $default;
    }

    public function has($key = null)
    {
        return array_key_exists($key, $this->data) && ! is_null($this->data[$key]);
    }

    public function set($key, $val = null)
    {
        return $this->data[$key] = $val;
    }

    public function clear()
    {
        return $this->data = [];
    }
    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }
}
