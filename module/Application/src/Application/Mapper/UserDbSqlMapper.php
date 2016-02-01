<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Mapper;

use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select;
use Zend\Stdlib\Hydrator\HydratorInterface;
use Application\Utils\DbConsts\DbViewUsers;
use Application\Utils\DbConsts\DbViewUserActivity;
use Application\Utils\DbConsts\DbViewMembership;
use Application\Utils\DbSelectColumns;

/**
 * Description of UserDbSqlMapper
 *
 * @author Alexander
 */
class UserDbSqlMapper extends ZendDbSqlMapper implements UserMapperInterface
{
    /**
     *
     * @var Zend\Stdlib\Hydrator\HydratorInterface 
     */
    protected $userSiteHydrator;
    
    public function __construct(
            \Zend\Db\Adapter\AdapterInterface $dbAdapter, 
            HydratorInterface $defaultHydrator, 
            HydratorInterface $userSiteHydrator,
            $objectPrototype, 
            $table, 
            $idFieldName) 
    {
        parent::__construct($dbAdapter, $defaultHydrator, $objectPrototype, $table, $idFieldName);
        $this->userSiteHydrator = $userSiteHydrator;
    }
    
    /**
     * {@inheritdoc}
     */
    public function findUsersOfSite($siteId, $order = null, $paginated = false) 
    {
        $sql = new Sql($this->dbAdapter);
        $membershipCols = array();
        foreach (DbSelectColumns::MEMBERSHIP as $col) {
            $membershipCols[DbViewMembership::TABLE.'_'.$col] = $col;
        }
        $activityCols = array();
        foreach(DbSelectColumns::USER_ACTIVITY as $col) {
            $activityCols[DbViewUserActivity::TABLE.'_'.$col] = $col;
        }
        $userCols = array();
        foreach(DbSelectColumns::USER as $col) {
            $userCols[DbViewUsers::TABLE.'_'.$col] = $col;
        }        
        $select = $sql->select()
                ->from(array('u' => DbViewUsers::TABLE))
                ->columns($userCols)
                ->join(array('a' => DbViewUserActivity::TABLE), 'u.'.DbViewUsers::USERID.' = a.'.DbViewUserActivity::USERID, $activityCols)
                ->join(array('m' => DbViewMembership::TABLE), 'm.'.DbViewMembership::USERID.' = u.'.DbViewUsers::USERID.' AND m.'.DbViewMembership::SITEID.' = a.'.DbViewUserActivity::SITEID, $membershipCols, Select::JOIN_LEFT)
                ->where(array(
                    'a.'.DbViewUserActivity::SITEID.' = ?' => $siteId,
                    '(m.'.DbViewMembership::JOINDATE.' IS NOT NULL OR (a.'.DbViewUserActivity::VOTES.' > 0) OR (a.'.DbViewUserActivity::REVISIONS.' > 0) or (a.'.DbViewUserActivity::PAGES.' > 0))',
                    ));
        if (is_array($order)) {
            $this->orderSelect($select, $order);            
        }
        $resultSet = new \Zend\Db\ResultSet\HydratingResultSet($this->userSiteHydrator, $this->objectPrototype);
        if ($paginated) {
            $adapter = new \Zend\Paginator\Adapter\DbSelect($select, $this->dbAdapter, $resultSet);
            return new \Zend\Paginator\Paginator($adapter);
        }        
        return $resultSet->initialize($this->fetch($sql, $select));        
    }
}
