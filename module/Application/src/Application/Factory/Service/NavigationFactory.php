<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Factory\Service;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Navigation\Service\AbstractNavigationFactory;

/**
 * Description of NavigationFactory
 *
 * @author Alexander
 */
class NavigationFactory extends AbstractNavigationFactory
{
    /**
     * @var Application\Service\SiteService;
     */
    protected $siteService;

    /**
     * @var Application\Service\UtilityService;
     */
    protected $utilityService;    

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $this->siteService = $serviceLocator->get('Application\Service\SiteServiceInterface');
        $this->utilityService = $serviceLocator->get('Application\Service\UtilityServiceInterface');
        return parent::createService($serviceLocator);
    }
    
    
    /**
     * {@inheritdoc}
     */
    protected function getPagesFromConfig($config = null)
    {
        $result = parent::getPagesFromConfig($config);
        $activeSite = $this->utilityService->getSiteId();
        $sites = $this->siteService->findAll();
        $sitePages = [];
        foreach ($sites as $site) {
            $isActive = $site->getId() === $activeSite;
            if ($isActive) {
                $result[0]['label'] = $site->getEnglishName();
            }
            $sitePages[] = [
                'label' => $site->getEnglishName(),
                'route' => 'select-site',
                'active' => $isActive,
                'query' => [
                    'siteId' => $site->getId()                        
                ]
            ];
        }
        $result[0]['pages'] = $sitePages;
        return $result;
    }
    
    /**
     * {@inheritdoc}
     */
    protected function getName() 
    {
        return 'default';
    }

}
