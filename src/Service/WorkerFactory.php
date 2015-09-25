<?php
namespace Zf2ResqueEx\Service;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;

/**
 * WorkerFactory
 *
 * @package Zf2ResqueEx\Service
 * @author Martin Meredith <martin@sourceguru.net>
 * @copyright 2015 Martin Meredith
 */
class WorkerFactory implements ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;

    /**
     * create
     *
     * @param array $queues
     *
     * @return \Zf2ResqueEx\Service\Worker
     */
    public function create($queues)
    {
        $worker = new Worker($queues);
        $worker->setServiceLocator($this->getServiceLocator());

        return $worker;
    }
}
