<?php

namespace Humweb\SlackPipe\Providers\Jira;

use Humweb\SlackPipe\Support\Config;
use Humweb\SlackPipe\Support\Encryption;
use JiraRestApi\Configuration\ArrayConfiguration;

/**
 * JiraConfig
 *
 * @package Humweb\SlackPipe\Providers\Jira
 */
class JiraConfig extends Config
{

    protected $mutators = [];

    /**
     * JiraConfig constructor.
     *
     * @param string $filename
     */
    public function __construct($filename)
    {
        parent::__construct($filename);

        $crypt = new Encryption('4bcd3fgh1j|<1mn0pq?st\/wxyz.!:>-');

        $this->addMutators('jiraPassword', function ($val) use ($crypt) {
            return $crypt->encrypt($val);
        });

        $this->addAccessor('jiraPassword', function ($val) use ($crypt) {
            return $crypt->decrypt($val);
        });
    }

    protected $provider        = 'jira';
    protected $protectedFields = ['jiraPassword'];

    /**
     * @return \JiraRestApi\Configuration\ArrayConfiguration
     */
    public function createConfigObject()
    {
        return new ArrayConfiguration($this->read());
    }
}
