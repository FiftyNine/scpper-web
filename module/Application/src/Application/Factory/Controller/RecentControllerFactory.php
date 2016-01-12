<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Factory\Controller;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Application\Controller\RecentController;

/**
 * Description of RecentControllerFactory
 *
 * @author Alexander
 */
class RecentControllerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator) 
    {
        $realLocator = $serviceLocator->getServiceLocator();
        $hubService = $realLocator->get('Application\Service\HubServiceInterface');
        $form = $realLocator->get('FormElementManager')->get('Application\Form\DateIntervalForm');
        return new RecentController($hubService, $form);
    }
}
