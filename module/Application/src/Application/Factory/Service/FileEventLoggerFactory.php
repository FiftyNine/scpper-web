<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Factory\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Application\Service\EventLogger;

/**
 * Description of FileEventLoggerFactory
 *
 * @author Alexander
 */
class FileEventLoggerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $logger = new \Zend\Log\Logger();
        $writer = new \Zend\Log\Writer\Stream('scpper.log');        
        $priority = \Zend\Log\Logger::WARN;
        if (defined('\SCPPER_DEBUG')) {
            $priority = \Zend\Log\Logger::DEBUG;
        }
        $filter = new \Zend\Log\Filter\Priority($priority);
        $writer->addFilter($filter);
        $logger->addWriter($writer);
        return new EventLogger($logger);
    }
}
