<?php

namespace Application\Factory\Mapper;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\Hydrator\ClassMethods;
use Zend\Stdlib\Hydrator\NamingStrategy\MapNamingStrategy;
use Application\Mapper\RevisionDbSqlMapper;
use Application\Utils\DbConsts\DbViewRevisionsAll;

class RevisionDbSqlMapperFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator) {        
        $dbAdapter = $serviceLocator->get('Zend\Db\Adapter\Adapter');
        $hydrator = new ClassMethods();
        $names = [
            DbViewRevisionsAll::REVISIONID => 'id',
            DbViewRevisionsAll::REVISIONINDEX => 'index',
        ];
        $hydrator->setNamingStrategy(new MapNamingStrategy($names));
        $hydrator->addStrategy(DbViewRevisionsAll::DATETIME, new \Zend\Stdlib\Hydrator\Strategy\DateTimeFormatterStrategy('Y-m-d H:i:s'));
        $prototype = $serviceLocator->get('RevisionPrototype');
        return new RevisionDbSqlMapper($dbAdapter, $hydrator, $prototype, DbViewRevisionsAll::TABLE, DbViewRevisionsAll::REVISIONID);
    }
}
