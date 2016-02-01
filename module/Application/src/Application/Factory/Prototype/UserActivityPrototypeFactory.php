<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Factory\Prototype;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Application\Model\UserActivity;

/**
 * Description of SitePrototypeFactory
 *
 * @author Alexander
 */
class UserActivityPrototypeFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator) 
    {
        $siteMapper = $serviceLocator->get('SiteMapper');        
        $userMapper = $serviceLocator->get('UserMapper');        
        $voteMapper = $serviceLocator->get('VoteMapper');
        $revisionMapper = $serviceLocator->get('RevisionMapper');
        $authorMapper = $serviceLocator->get('AuthorshipMapper');
        return new UserActivity($siteMapper, $userMapper, $voteMapper, $revisionMapper, $authorMapper);
    }
}
