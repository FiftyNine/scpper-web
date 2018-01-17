<?php

namespace Application\Factory\Mapper;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\Hydrator\ClassMethods;
use Application\Mapper\PageDbSqlMapper;
use Application\Utils\DbConsts\DbViewPagesAll;

class PageDbSqlMapperFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator) {        
        $dbAdapter = $serviceLocator->get('Zend\Db\Adapter\Adapter');
        $hydrator = new ClassMethods();
        $namingStrat = new \Zend\Stdlib\Hydrator\NamingStrategy\MapNamingStrategy([
            DbViewPagesAll::PAGEID => 'id',
            DbViewPagesAll::PAGENAME => 'name',
            DbViewPagesAll::REVISIONS => 'revisionCount',
            DbViewPagesAll::STATUSID => 'status',
            DbViewPagesAll::STATUS => '',
            DbViewPagesAll::KINDID => 'kind',
            DbViewPagesAll::KIND => ''
        ]);
        $hydrator->setNamingStrategy($namingStrat);
        $hydrator->addStrategy(DbViewPagesAll::CREATIONDATE, new \Zend\Stdlib\Hydrator\Strategy\DateTimeFormatterStrategy('Y-m-d H:i:s'));
        $hydrator->addStrategy(DbViewPagesAll::LASTUPDATE, new \Zend\Stdlib\Hydrator\Strategy\DateTimeFormatterStrategy('Y-m-d H:i:s'));
        $prototype = $serviceLocator->get('PagePrototype');
        return new PageDbSqlMapper($dbAdapter, $hydrator, $prototype, DbViewPagesAll::TABLE, DbViewPagesAll::PAGEID);
    }
}
