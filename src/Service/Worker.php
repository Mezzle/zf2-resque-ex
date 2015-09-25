<?php
namespace Zf2ResqueEx\Service;

use Resque_Worker;
use Zend\EventManager\EventManagerAwareInterface;
use Zend\EventManager\EventManagerAwareTrait;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;

/**
 * Worker
 *
 * @package Zf2ResqueEx\Service
 * @author Martin Meredith <martin@sourceguru.net>
 * @copyright 2015 Martin Meredith
 */
class Worker extends Resque_Worker implements EventManagerAwareInterface, ServiceLocatorAwareInterface
{
    use EventManagerAwareTrait;
    use ServiceLocatorAwareTrait;

    public function reserve($timeout = null)
    {
        $queues = $this->queues();

        if (!is_array($queues)) {
            return false;
        }

        foreach ($queues as $queue) {
            $this->log(
                ['message' => 'Checking ' . $queue, 'data' => ['type' => 'check', 'queue' => $queue]],
                self::LOG_TYPE_DEBUG
            );

            $job = Job::reserve($queue, $this->getServiceLocator());

            if ($job) {
                $this->log(
                    ['message' => 'Found job on ' . $queue, 'data' => ['type' => 'found', 'queue' => $queue]],
                    self::LOG_TYPE_DEBUG
                );

                return $job;
            }
        }

        return false;
    }
}
