<?php
namespace Zf2ResqueEx\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zf2ResqueEx\Options\ResqueOptions;

/**
 * ResqueProxyFactory
 *
 * @package Zf2ResqueEx\Service
 * @author Martin Meredith <martin@sourceguru.net>
 * @copyright 2015 Martin Meredith
 */
class ResqueProxyFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var array $config */
        $config = $serviceLocator->get('Configuration');

        if (!isset($config['zf2_resque'])) {
            $config = [];
        } else {
            $config = $config['zf2_resque'];
        }

        $config = new ResqueOptions($config);

        $resque_proxy = new ResqueProxy($config);

        return $resque_proxy;
    }
}
