<?php
namespace Zf2ResqueEx\Console;

use Zend\Console\ColorInterface;
use Zend\Mvc\Controller\AbstractConsoleController;
use Zend\Text\Table\Table;

/**
 * Controller
 *
 * @package Zf2ResqueEx\Console
 * @author Martin Meredith <martin@sourceguru.net>
 * @copyright 2015 Martin Meredith
 */
class Controller extends AbstractConsoleController
{
    /**
     * getResqueService
     *
     * @return \Zf2ResqueEx\Service\ResqueProxy
     */
    protected function getResqueService()
    {
        return $this->getServiceLocator()->get('Resque');
    }

    public function queuesAction()
    {
        $resque = $this->getResqueService();

        $queue_stats = [];

        foreach ($resque->queues() as $queue) {
            $queue_stats[$queue] = [
                'size' => $resque->size($queue)
            ];
        }

        $table = new Table(
            [
                'columnWidths' => [25, 10]
            ]
        );

        $table->appendRow(['Queue', 'Size']);

        foreach ($queue_stats as $key => $queue) {
            $table->appendRow(
                [
                    $key,
                    $queue['size']
                ]
            );
        }

        echo $this->getConsole()->colorize(
            'Queue Statistics',
            ColorInterface::RED
        );

        echo $table;
    }
}
