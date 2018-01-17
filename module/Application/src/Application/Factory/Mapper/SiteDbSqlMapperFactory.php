<?php

namespace Application\Factory\Mapper;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\Hydrator\ClassMethods;
use Zend\Stdlib\Hydrator\NamingStrategy\MapNamingStrategy;
use Application\Mapper\SiteDbSqlMapper;
use Application\Utils\DbConsts\DbViewSites;


class SiteDbSqlMapperFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator) {        
        $dbAdapter = $serviceLocator->get('Zend\Db\Adapter\Adapter');
        $hydrator = new ClassMethods();
        $names = [
            DbViewSites::SITEID => 'id',
        ];
        $hydrator->setNamingStrategy(new MapNamingStrategy($names));
        $prototype = $serviceLocator->get('SitePrototype');
        return new SiteDbSqlMapper($dbAdapter, $hydrator, $prototype, DbViewSites::TABLE, DbViewSites::SITEID);
    }
}