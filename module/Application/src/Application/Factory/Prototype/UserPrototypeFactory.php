<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Factory\Prototype;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Application\Model\User;

/**
 * Description of SitePrototypeFactory
 *
 * @author Alexander
 */
class UserPrototypeFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator) 
    {
        $activityMapper = $serviceLocator->get('UserActivityMapper');
        $membershipMapper = $serviceLocator->get('MembershipMapper');
        return new User($activityMapper, $membershipMapper);
    }
}
