<?php

namespace Application\Factory\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Application\Service\PageService;

class PageServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator) {
        $mapper = $serviceLocator->get('PageMapper');
        $userMapper = $serviceLocator->get('UserMapper');
        $authorshipMapper = $serviceLocator->get('AuthorshipMapper');
        return new PageService($mapper, $userMapper, $authorshipMapper);
    }
}
