<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Utils;

/**
 *
 * @author Alexander
 */
interface PostInitializationInterface 
{
    /**
     * Initializer for a service to perform final initialization 
     * after ServiceManager already called initializers for
     * EventManagerAwareInterface and ServiceManagerAwareInterface
     */
    public function PostInitialize();
}
