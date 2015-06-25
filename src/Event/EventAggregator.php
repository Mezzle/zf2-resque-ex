<?php
namespace Zf2ResqueEx\Event;

use Exception;
use Resque_Event;
use Resque_Job;
use Resque_Worker;
use Zend\EventManager\EventManagerAwareInterface;
use Zend\EventManager\EventManagerAwareTrait;

/**
 * EventAggregator
 *
 * @package Zf2ResqueEx\Event
 * @author Martin Meredith <martin@sourceguru.net>
 * @copyright 2015 Martin Meredith
 */
class EventAggregator implements EventManagerAwareInterface
{
    use EventManagerAwareTrait;

    /**
     * @var array $events
     */
    protected $events = [
        'beforeFirstFork',
        'beforeFork',
        'afterFork',
        'beforePerform',
        'afterPerform',
        'onFailure',
        'beforeEnqueue',
        'afterEnqueue'
    ];

    /**
     * afterEnqueue
     *
     * @param string $class
     * @param array $args
     * @param string $queue
     * @param string $id
     */
    public function afterEnqueue($class, array $args, $queue, $id)
    {
        $this->getEventManager()
            ->trigger(
                'resque.afterEnqueue',
                $this,
                [
                    'class' => $class,
                    'args' => $args,
                    'queue' => $queue,
                    'id' => $id
                ]
            );
    }

    /**
     * afterFork
     *
     * @param \Resque_Job $job
     */
    public function afterFork(Resque_Job $job)
    {
        $this->getEventManager()
            ->trigger(
                'resque.afterFork',
                $job
            );
    }

    /**
     * afterPerform
     *
     * @param Resque_Job $job
     */
    public function afterPerform(Resque_Job $job)
    {
        $this->getEventManager()
            ->trigger(
                'resque.afterPerform',
                $job
            );
    }

    /**
     * attach
     */
    public function attach()
    {
        foreach ($this->events as $event) {
            Resque_Event::listen($event, [$this, $event]);
        }
    }

    /**
     * beforeEnqueue
     *
     * @param string $class
     * @param array $args
     * @param string $queue
     * @param string $id
     */
    public function beforeEnqueue($class, $args, $queue, $id)
    {
        $this->getEventManager()
            ->trigger(
                'resque.beforeEnqueue',
                $this,
                [
                    'class' => $class,
                    'args' => $args,
                    'queue' => $queue,
                    'id' => $id
                ]
            );
    }

    /**
     * beforeFirstFork
     *
     * @param \Resque_Worker $worker
     */
    public function beforeFirstFork(Resque_Worker $worker)
    {
        $this->getEventManager()
            ->trigger(
                'resque.beforeFirstFork',
                $worker
            );
    }

    /**
     * beforeFork
     *
     * @param Resque_Job $job
     */
    public function beforeFork(Resque_Job $job)
    {
        $this->getEventManager()
            ->trigger(
                'resque.beforeFork',
                $job
            );
    }

    /**
     * beforePerform
     *
     * @param Resque_Job $job
     */
    public function beforePerform(Resque_Job $job)
    {
        $this->getEventManager()
            ->trigger(
                'resque.beforePerform',
                $job
            );
    }

    /**
     * detach
     */
    public function detach()
    {
        foreach ($this->events as $event) {
            Resque_Event::stopListening($event, [$this, $event]);
        }
    }

    /**
     * onFailure
     *
     * @param Exception $e
     * @param Resque_Job $job
     */
    public function onFailure(Exception $e, Resque_Job $job)
    {
        $this->getEventManager()
            ->trigger(
                'resque.onFailure',
                $job,
                ['exception' => $e]
            );
    }
}
