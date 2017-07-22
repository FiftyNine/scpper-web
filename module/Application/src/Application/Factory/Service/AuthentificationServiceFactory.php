<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Factory\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Adapter\DbTable\CallbackCheckAdapter;

/**
 * Description of AuthentificationServiceFactory
 *
 * @author Alexander
 */
class AuthentificationServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $session = $serviceLocator->get('Zend\Session\SessionManager');
        $dbAdapter = $serviceLocator->get('Zend\Db\Adapter\Adapter');
        $authAdapter = new CallbackCheckAdapter(
                $dbAdapter, 
                'scpper_users', 
                'user', 
                'password', 
                function ($hash, $password) {
                    return password_verify($password, $hash);
                }
        );
        $sessionStorage = new \Zend\Authentication\Storage\Session(null, null, $session);
        $service = new AuthenticationService($sessionStorage, $authAdapter);        
        return $service;
    }
}
