<?php

namespace Humweb\SlackPipe\Providers\Jira;

use Humweb\SlackPipe\Support\Config;
use Humweb\SlackPipe\Support\Container;
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
     * @param bool   $eager
     */
    public function __construct($filename, $eager = false)
    {

        $crypt = Container::getInstance()->get('crypt');

        $this->addMutators('jiraPassword', function ($val) use ($crypt) {
            return $crypt->encrypt($val);
        });

        $this->addAccessor('jiraPassword', function ($val) use ($crypt) {
            return $crypt->decrypt($val);
        });

        parent::__construct($filename, $eager);
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
