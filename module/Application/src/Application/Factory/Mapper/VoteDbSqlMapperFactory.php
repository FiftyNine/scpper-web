<?php

namespace Application\Factory\Mapper;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\Hydrator\ClassMethods;
use Zend\Stdlib\Hydrator\Strategy\DateTimeFormatterStrategy;
use Application\Mapper\VoteDbSqlMapper;
use Application\Utils\DbConsts\DbViewVotes;

class VoteDbSqlMapperFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator) {        
        $dbAdapter = $serviceLocator->get('Zend\Db\Adapter\Adapter');
        $hydrator = new ClassMethods();
        $hydrator->addStrategy(DbViewVotes::DATETIME, new DateTimeFormatterStrategy('Y-m-d H:i:s'));
        $prototype = $serviceLocator->get('VotePrototype');
        return new VoteDbSqlMapper($dbAdapter, $hydrator, $prototype, DbViewVotes::TABLE, DbViewVotes::__ID);
    }
}
