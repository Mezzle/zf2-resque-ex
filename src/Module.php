<?php

namespace Zf2ResqueEx;

use Zend\Console\Adapter\AdapterInterface;
use Zend\EventManager\EventInterface;
use Zend\EventManager\EventManagerAwareInterface;
use Zend\EventManager\EventManagerAwareTrait;
use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ConsoleBannerProviderInterface;
use Zend\ModuleManager\Feature\ConsoleUsageProviderInterface;
use Zend\ModuleManager\Feature\ControllerProviderInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;

/**
 * Module
 *
 * @package Zf2ResqueEx
 * @author Martin Meredith <martin@sourceguru.net>
 * @copyright 2015 Martin Meredith
 */
class Module implements
    ConfigProviderInterface,
    ServiceProviderInterface,
    ConsoleBannerProviderInterface,
    ConsoleUsageProviderInterface,
    ControllerProviderInterface,
    BootstrapListenerInterface,
    EventManagerAwareInterface,
    ServiceLocatorAwareInterface
{
    use EventManagerAwareTrait;
    use ServiceLocatorAwareTrait;

    /**
     * eventCallback
     *
     * @param ...$args
     */
    public function eventCallback(...$args)
    {
        $this->getEventManager()->trigger(
            sprintf('resque.%s')
        );
    }

    /**
     * getAutoloaderConfig
     *
     * @return array
     */
    public function getAutoloaderConfig()
    {
        return [
            'Zend\Loader\StandardAutoloader' => [
                'namespaces' => [
                    __NAMESPACE__ => realpath(__DIR__ . '/../src/'),
                ],
            ],
        ];
    }

    /**
     * Returns configuration to merge with application configuration
     *
     * @return array|\Traversable
     */
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    /**
     * Returns a string containing a banner text, that describes the module
     * and/or the application. The banner is shown in the console window, when
     * the user supplies invalid command-line parameters or invokes the
     * application with no parameters.
     *
     * The method is called with active Zend\Console\Adapter\AdapterInterface
     * that can be used to directly access Console and send output.
     *
     * @param AdapterInterface $console
     *
     * @return string|null
     */
    public function getConsoleBanner(AdapterInterface $console)
    {
        // TODO: Implement getConsoleBanner() method.
    }

    /**
     * Returns an array or a string containing usage information for this
     * module's Console commands. The method is called with active
     * Zend\Console\Adapter\AdapterInterface that can be used to directly
     * access Console and send output.
     *
     * If the result is a string it will be shown directly in the console
     * window. If the result is an array, its contents will be formatted to
     * console window width. The array must have the following format:
     *
     *     return array(
     *                'Usage information line that should be shown as-is',
     *                'Another line of usage info',
     *
     *                '--parameter'        =>   'A short description of that
     * parameter',
     *                '-another-parameter' =>   'A short description of another
     * parameter',
     *                ...
     *            )
     *
     * @param AdapterInterface $console
     *
     * @return array|string|null
     */
    public function getConsoleUsage(AdapterInterface $console)
    {
        // TODO: Implement getConsoleUsage() method.
    }

    /**
     * Expected to return \Zend\ServiceManager\Config object or array to seed
     * such an object.
     *
     * @return array|\Zend\ServiceManager\Config
     */
    public function getControllerConfig()
    {
        // TODO: Implement getControllerConfig() method.
    }

    /**
     * getServiceConfig
     *
     * @return array
     */
    public function getServiceConfig()
    {
        return [
            'invokables' => [
                Event\EventAggregator::class => Event\EventAggregator::class,
                Service\WorkerFactory::class => Service\WorkerFactory::class,
            ],
            'factories' => [
                'Resque' => Service\ResqueProxyFactory::class,
            ],
        ];
    }

    /**
     * Listen to the bootstrap event
     *
     * @param EventInterface $e
     *
     * @return array
     */
    public function onBootstrap(EventInterface $e)
    {
        /** @var \Zend\Mvc\Application $app */
        $app = $e->getParam('application');

        $this->setServiceLocator($app->getServiceManager());
        $this->setEventManager($app->getEventManager());

        $this->bootstrapEvents();
    }

    /**
     * bootstrapEvents
     */
    protected function bootstrapEvents()
    {
        $this->getServiceLocator()
            ->get(Event\EventAggregator::class)
            ->attach();
    }
}
