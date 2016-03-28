<?php

namespace Application\Factory\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Application\Service\VoteService;

class VoteServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator) {
        $mapper = $serviceLocator->get('VoteMapper');
        $userMapper = $serviceLocator->get('UserMapper');
        $pageMapper = $serviceLocator->get('PageMapper');
        return new VoteService($mapper, $userMapper, $pageMapper);
    }
}
