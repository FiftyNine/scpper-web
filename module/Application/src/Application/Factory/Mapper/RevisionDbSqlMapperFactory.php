<?php

namespace Application\Factory\Mapper;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\Hydrator\ClassMethods;
use Application\Mapper\RevisionDbSqlMapper;
use Application\Utils\DbConsts\DbViewRevisions;

class RevisionDbSqlMapperFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator) {        
        $dbAdapter = $serviceLocator->get('Zend\Db\Adapter\Adapter');
        $hydrator = new ClassMethods();
        $prototype = $serviceLocator->get('RevisionPrototype');
        return new RevisionDbSqlMapper($dbAdapter, $hydrator, $prototype, DbViewRevisions::TABLE, DbViewRevisions::REVISIONID);
    }
}
