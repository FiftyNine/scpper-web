<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Utils;

use Zend\ServiceManager\InitializerInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Description of PostInitializer
 *
 * @author Alexander
 */
class PostInitializer implements InitializerInterface
{
    public function initialize($instance, ServiceLocatorInterface $serviceLocator) {
        if ($instance instanceOf PostInitializationInterface) {
            $instance->PostInitialize();
        }
    }
}
