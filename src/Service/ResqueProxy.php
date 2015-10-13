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
    const KEY_FAILED = 'failed';
    const KEY_STAT_FAILED = 'stat:failed';
    const KEY_STAT_PROCESSED = 'stat:processed';
    const KEY_WORKERS = 'workers';

    const PATTERN_WORKER = 'worker:%s';
    const PATTERN_WORKER_PROCESSED = 'stat:processed:%s';
    const PATTERN_WORKER_STARTED = 'worker:%s:started';

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
     */
    public function connect()
    {
        Resque::setBackend(
            $this->options->getServer(),
            $this->options->getDatabase(),
            $this->options->getPassword()
        );
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
     * getFailed
     *
     * @return int
     */
    public function getFailed()
    {
        return (int) $this->redis()->get(self::KEY_STAT_FAILED);
    }

    /**
     * redis
     *
     * @return \RedisApi
     *
     */
    public function redis()
    {
        return Resque::redis();
    }

    /**
     * getProcessed
     *
     * @return int
     */
    public function getProcessed()
    {
        return (int) $this->redis()->get(self::KEY_STAT_PROCESSED);
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

    /**
     * size
     *
     * @param $queue
     *
     * @return int
     */
    public function size($queue)
    {
        return Resque::size($queue);
    }

    /**
     * getFailuresPerQueue
     *
     * @return array
     */
    public function getFailuresPerQueue()
    {
        $failure_count = $this->redis()->llen(self::KEY_FAILED);

        $failures = [];

        $failures_json = $this->redis()
            ->lRange(
                self::KEY_FAILED,
                0,
                $failure_count - 1
            );

        foreach ($failures_json as $id => $failure) {
            $failure = json_decode($failure, true);

            $queue = $failure['queue'];

            $failures[$queue][] = [
                'id' => $id,
                'failure' => $failure,
            ];
        }

        return $failures;
    }
}

