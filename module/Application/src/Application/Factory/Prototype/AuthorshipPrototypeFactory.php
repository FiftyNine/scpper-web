<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Factory\Prototype;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Application\Model\Authorship;

/**
 * Description of SitePrototypeFactory
 *
 * @author Alexander
 */
class AuthorshipPrototypeFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator) 
    {
        $userMapper = $serviceLocator->get('UserMapper');        
        $pageMapper = $serviceLocator->get('PageMapper');
        return new Authorship($userMapper, $pageMapper);
    }
}
