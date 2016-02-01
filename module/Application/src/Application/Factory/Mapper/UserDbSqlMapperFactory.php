<?php

namespace Application\Factory\Mapper;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Application\Utils\DbConsts\DbViewUsers; 
use Application\Utils\DbConsts\DbViewMembership; 
use Application\Utils\DbConsts\DbViewUserActivity; 
use Application\Mapper\UserDbSqlMapper;
use Application\Hydrator\UserDbHydrator;
use Application\Hydrator\UserActivityDbHydrator;
use Application\Hydrator\MembershipDbHydrator;
use Application\Hydrator\UserSiteDbHydrator;

class UserDbSqlMapperFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator) {
        $dbAdapter = $serviceLocator->get('Zend\Db\Adapter\Adapter');
        // Prototypes
        $membershipPrototype = $serviceLocator->get('MembershipPrototype');
        $activityPrototype = $serviceLocator->get('UserActivityPrototype');
        $userPrototype = $serviceLocator->get('UserPrototype');        
        // Hydrators
        $defaultHydrator = new UserDbHydrator();       
        $membershipHydrator = new MembershipDbHydrator(DbViewMembership::TABLE);        
        $activityHydrator = new UserActivityDbHydrator(DbViewUserActivity::TABLE);        
        $userHydrator = new UserSiteDbHydrator($membershipHydrator, $membershipPrototype, $activityHydrator, $activityPrototype, DbViewUsers::TABLE);        
        return new UserDbSqlMapper($dbAdapter, $defaultHydrator, $userHydrator, $userPrototype, DbViewUsers::TABLE, DbViewUsers::USERID);
    }
}
