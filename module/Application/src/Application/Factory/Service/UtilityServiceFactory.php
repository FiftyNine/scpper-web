<?php

namespace Application\Factory\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Application\Service\UtilityService;

class UtilityServiceFactory implements FactoryInterface
{
    /**
     * Creates a UtilityFactoryInterface object
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator) {
        $siteService = $serviceLocator->get('Application\Service\SiteServiceInterface');
        $request = $serviceLocator->get('Request');
        $dbAdapter = $serviceLocator->get('Zend\Db\Adapter\Adapter');
        return new UtilityService($siteService, $request, $dbAdapter);
    }
}

