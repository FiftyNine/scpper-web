<?php

namespace Application\Factory\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Application\Service\RevisionService;

class RevisionServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator) {
        $mapper = $serviceLocator->get('RevisionMapper');
        $userMapper = $serviceLocator->get('UserMapper');
        return new RevisionService($mapper, $userMapper);
    }
}
