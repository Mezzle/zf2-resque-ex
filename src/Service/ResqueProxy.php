<?php
namespace Zf2ResqueEx\Service;

use Resque;
use Zf2ResqueEx\Options\ResqueOptions;

/**
 * ResqueProxy
 *
 * @package Zf2ResqueEx\Service
 * @author Martin Meredith <martin@sourceguru.net>
 * @copyright 2015 Martin Meredith
 */
class ResqueProxy
{
    /** @var array|ResqueOptions $options */
    protected $options;

    /**
     * __construct
     *
     * @param array $options
     */
    public function __construct($options = [])
    {
        $this->setOptions($options);
    }

    /**
     * setOptions
     *
     * @param array|ResqueOptions $options
     *
     * @return $this
     */
    public function setOptions($options)
    {
        if (!$options instanceof ResqueOptions) {
            $options = new ResqueOptions($options);
        }

        $this->options = $options;

        return $this;
    }

    /**
     * setBackend
     *
     * @param string $server
     * @param int $database
     * @param string $namespace
     * @param string|null $password
     */
    public function setBackend($server, $database = 0, $namespace = 'resque', $password = null)
    {
        Resque::setBackend($server, $database, $namespace, $password);
    }

    /**
     * redis
     *
     * @return \Resque_Redis
     */
    public function redis()
    {
        return Resque::redis();
    }

    /**
     * size
     *
     * @param $queue
     * @return int
     */
    public function size($queue)
    {
        return Resque::size($queue);
    }

    /**
     * enqueue
     *
     * @param $queue
     * @param $class
     * @param null $args
     * @param bool|false $trackStatus
     *
     * @return string
     */
    public function enqueue($queue, $class, $args = null, $trackStatus = false)
    {
        return Resque::enqueue($queue, $class, $args, $trackStatus);
    }

    /**
     * queues
     *
     * @return array
     */
    public function queues()
    {
        return Resque::queues();
    }
}

