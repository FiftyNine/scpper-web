<?php

namespace Application\Factory\Controller;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Application\Controller\Roundup2018Controller;

class Roundup2018ControllerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator) 
    {
        $realLocator = $serviceLocator->getServiceLocator();
        $hubService = $realLocator->get('Application\Service\HubServiceInterface');        
        return new Roundup2018Controller($hubService);
    }
}
