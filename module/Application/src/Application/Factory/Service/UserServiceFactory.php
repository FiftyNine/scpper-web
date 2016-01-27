<?php

namespace Application\Factory\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Application\Service\UserService;

class UserServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator) 
    {
        $userMapper = $serviceLocator->get('UserMapper');
        $membershipMapper = $serviceLocator->get('MembershipMapper');
        return new UserService($userMapper, $membershipMapper);
    }
}