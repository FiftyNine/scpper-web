<?php

namespace Application\Factory\Mapper;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\Hydrator\ClassMethods;
use Application\Mapper\ZendDbSqlMapper;


class SiteDbSqlMapperFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator) {        
        $dbAdapter = $serviceLocator->get('Zend\Db\Adapter\Adapter');
        $hydrator = new ClassMethods();
        $prototype = $serviceLocator->get('SitePrototype');
        return new ZendDbSqlMapper($dbAdapter, $hydrator, $prototype, 'sites', 'WikidotId');
    }
}