<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Factory\Controller;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Application\Controller\ActivityController;

/**
 * Description of RecentControllerFactory
 *
 * @author Alexander
 */
class ActivityControllerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator) 
    {
        $realLocator = $serviceLocator->getServiceLocator();
        $hubService = $realLocator->get('Application\Service\HubServiceInterface');
        $form = $realLocator->get('FormElementManager')->get('Application\Form\DateIntervalForm');
        return new ActivityController($hubService, $form);
    }
}
