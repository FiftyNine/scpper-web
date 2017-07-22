<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Factory\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Session\SessionManager;
use Zend\Session\Container;
use Zend\Session\Config\SessionConfig;

/**
 * Description of SessionManagerFactory
 *
 * @author Alexander
 */
class SessionManagerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator) {
        $config = $serviceLocator->get('config');
        if (! isset($config['session'])) {
            $sessionManager = new SessionManager();
            Container::setDefaultManager($sessionManager);
            return $sessionManager;
        }

        $session = $config['session'];

        $sessionConfig = null;
        if (isset($session['config'])) {
            $class = isset($session['config']['class'])
                ?  $session['config']['class']
                : SessionConfig::class;

            $options = isset($session['config']['options'])
                ?  $session['config']['options']
                : [];

            $sessionConfig = new $class();
            $sessionConfig->setOptions($options);
        }

        $sessionStorage = null;
        if (isset($session['storage'])) {
            $class = $session['storage'];
            $sessionStorage = new $class();
        }

        $sessionSaveHandler = null;
        if (isset($session['save_handler'])) {
            // class should be fetched from service manager
            // since it will require constructor arguments
            $sessionSaveHandler = $serviceLocator->get($session['save_handler']);
        }

        $sessionManager = new SessionManager(
            $sessionConfig,
            $sessionStorage,
            $sessionSaveHandler
        );

        Container::setDefaultManager($sessionManager);
        return $sessionManager;
    }
}
