<?php

namespace Application\Factory\Mapper;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\Hydrator\ClassMethods;
use Zend\Stdlib\Hydrator\Aggregate\AggregateHydrator;
use Zend\Stdlib\Hydrator\NamingStrategy\MapNamingStrategy;
use Application\Model\User;
use Application\Hydrator\UserMembershipHydrator;
use Application\Mapper\UserDbSqlMapper;
use Application\Utils\DbConsts\DbViewUsers; 
use Application\Utils\DbConsts\DbViewMembership;

class UserDbSqlMapperFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator) {
        $dbAdapter = $serviceLocator->get('Zend\Db\Adapter\Adapter');
        $hydrator = new AggregateHydrator();
        $baseHydrator = new ClassMethods(); 
        $namingStrat = new MapNamingStrategy(array(
            DbViewUsers::USERID => 'id',
            DbViewUsers::WIKIDOTNAME => 'name'
        ));        
        $baseHydrator->setNamingStrategy($namingStrat);
        $hydrator->add($baseHydrator);
        $hydrator->add(new UserMembershipHydrator());
        $prototype = new User();
        return new UserDbSqlMapper($dbAdapter, $hydrator, $prototype, 'users', 'WikidotId');
    }
}
