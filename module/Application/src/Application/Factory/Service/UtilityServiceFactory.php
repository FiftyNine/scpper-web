<?php

namespace Application\Factory\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Application\Form\SearchForm;
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
        $cookies = $request->getHeaders('Cookie');
        if ($cookies && $cookies->offsetExists(UtilityService::SITE_ID_COOKIE)) {
            $siteId = (int)$cookies->offsetGet(UtilityService::SITE_ID_COOKIE);
        } else {
            $siteId = UtilityService::ENGLISH_SITE_ID;
        }
        $searchForm = $serviceLocator->get('FormElementManager')->get('Application\Form\SearchForm');
        return new UtilityService($siteService, $dbAdapter, $searchForm, $siteId);
    }
}

