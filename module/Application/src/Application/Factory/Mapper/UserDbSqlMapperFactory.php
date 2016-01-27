<?php

namespace Application\Factory\Mapper;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\Hydrator\ClassMethods;
use Zend\Stdlib\Hydrator\NamingStrategy\MapNamingStrategy;
use Application\Model\User;
use Application\Utils\DbConsts\DbViewUsers; 
use Application\Mapper\ZendDbSqlMapper;

class UserDbSqlMapperFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator) {
        $dbAdapter = $serviceLocator->get('Zend\Db\Adapter\Adapter');
        $hydrator = new ClassMethods(); 
        $namingStrat = new MapNamingStrategy(array(
            DbViewUsers::USERID => 'id',
            DbViewUsers::WIKIDOTNAME => 'name'
        ));        
        $hydrator->setNamingStrategy($namingStrat);
        $activityMapper = $serviceLocator->get('UserActivityMapper');
        $membershipMapper = $serviceLocator->get('MembershipMapper');
        $prototype = new User($activityMapper, $membershipMapper);
        return new ZendDbSqlMapper($dbAdapter, $hydrator, $prototype, 'view_users', DbViewUsers::USERID);
    }
}
