<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Factory\Prototype;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Application\Model\Membership;

/**
 * Description of SitePrototypeFactory
 *
 * @author Alexander
 */
class MembershipPrototypeFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator) 
    {
        $siteMapper = $serviceLocator->get('SiteMapper');
        $userMapper = $serviceLocator->get('UserMapper');
        return new Membership($siteMapper, $userMapper);
    }
}
