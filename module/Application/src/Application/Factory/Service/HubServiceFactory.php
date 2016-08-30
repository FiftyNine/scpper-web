<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Factory\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Application\Service\HubService;

/**
 * Description of HubServiceFactory
 *
 * @author Alexander
 */
class HubServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator) {
        $siteService = $serviceLocator->get('Application\Service\SiteServiceInterface');
        $userService = $serviceLocator->get('Application\Service\UserServiceInterface');
        $pageService = $serviceLocator->get('Application\Service\PageServiceInterface');
        $revisionService = $serviceLocator->get('Application\Service\RevisionServiceInterface');
        $voteService = $serviceLocator->get('Application\Service\VoteServiceInterface');
        $tagService = $serviceLocator->get('Application\Service\TagServiceInterface');
        $utilityService = $serviceLocator->get('Application\Service\UtilityServiceInterface');
        return new HubService($siteService, $userService, $pageService, $revisionService, $voteService, $tagService, $utilityService);
    }
}
