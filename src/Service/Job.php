<?php
namespace Zf2ResqueEx\Service;

use Exception;
use Resque;
use Resque_Exception;
use Resque_Job;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Job
 *
 * @package Zf2ResqueEx\Service
 * @author Martin Meredith <martin@sourceguru.net>
 * @copyright 2015 Martin Meredith
 */
class Job extends Resque_Job implements ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;

    /**
     * @var object|null $instance
     */
    protected $instance;

    /**
     * reserve
     *
     * @param string $queue
     * @param \Zend\ServiceManager\ServiceLocatorInterface|null $service_locator
     *
     * @return bool|\Zf2ResqueEx\Service\Job
     */
    public static function reserve($queue, ServiceLocatorInterface $service_locator = null)
    {
        $payload = Resque::pop($queue);

        if (!is_array($payload)) {
            return false;
        }

        $job = new Job($queue, $payload);

        $job->setServiceLocator($service_locator);

        return $job;
    }

    /**
     * getInstance
     *
     * @return null|object
     *
     * @throws Resque_Exception
     */
    public function getInstance()
    {
        if (!is_null($this->instance)) {
            return $this->instance;
        }

        if (class_exists('Resque_Job_Creator')) {
            $this->instance = \Resque_Job_Creator::createJob($this->payload['class'], $this->getArguments());
        } else {
            try {
                $this->instance = $this->getServiceLocator()->get($this->payload['class']);

                $this->instance->job = $this;
                $this->instance->args = $this->getArguments();
                $this->instance->queue = $this->queue;

                if (!method_exists($this->instance, 'perform')) {
                    throw new Resque_Exception(
                        'Job class ' . $this->payload['class'] . ' does not contain a perform method.'
                    );
                }
            } catch (Exception $e) {
                throw new Resque_Exception($e->getMessage());
            }
        }

        return $this->instance;
    }
}
