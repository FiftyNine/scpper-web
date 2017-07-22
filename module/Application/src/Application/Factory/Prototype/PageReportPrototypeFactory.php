<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Factory\Prototype;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Application\Model\PageReport;

/**
 * Description of PageReportPrototypeFactory
 *
 * @author Alexander
 */
class PageReportPrototypeFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator) 
    {
        $pageMapper = $serviceLocator->get('PageMapper');
        return new PageReport($pageMapper);
    }
}
