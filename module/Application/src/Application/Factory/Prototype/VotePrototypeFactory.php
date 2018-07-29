<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Factory\Prototype;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Application\Model\Vote;

/**
 * Description of SitePrototypeFactory
 *
 * @author Alexander
 */
class VotePrototypeFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator) 
    {
        $userMapper = $serviceLocator->get('UserMapper');
        $pageMapper = $serviceLocator->get('PageMapper');
        $voteMapper = $serviceLocator->get('VoteMapper');
        return new Vote($userMapper, $pageMapper, $voteMapper);
    }
}
