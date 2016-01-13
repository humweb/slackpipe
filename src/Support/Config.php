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
    protected $crypt;
    protected $accessors = [];

    protected $mutators = [];

    /**
     * JiraConfig constructor.
     *
     * @param string $filename
     */
    public function __construct($filename)
    {
        $this->filename = $filename;
        $this->crypt    = new Encryption('4bcd3fgh1j|<1mn0pq?st\/wxyz.!:>-');
        $this->storage  = new Storage;
    }

    static public function factory($filename)
    {
        if ($config = Asserts::hasCustomConfig($filename)) {
            return new $config($filename);
        }

        return new static($filename);
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
        $data = $this->dehydrate($data);

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
        return $this->hydrate(include($this->filePath()));
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
}
