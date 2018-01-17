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
use Application\Utils\Order;

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
    
    protected function getUsersOfSiteConditions($siteId)
    {
        return [
            'a.'.DbViewUserActivity::SITEID.' = ?' => $siteId,                    
            '(m.'.DbViewMembership::JOINDATE.' IS NOT NULL OR (a.'.DbViewUserActivity::VOTES.' > 0) OR (a.'.DbViewUserActivity::REVISIONS.' > 0) or (a.'.DbViewUserActivity::PAGES.' > 0))',
        ];        
    }
    
    /**
     * Returns a list of all users who are members of site or has any kind of activity on the site
     * @param int $siteId
     * @param array[string] $conditions
     * @param array[string]int $order
     * @param bool $paginated
     * @return UserInterface[]|Paginator
     */
    protected function findUsersOfSiteInternal($conditions = null, $order = null, $paginated = false) 
    {
        $sql = new Sql($this->dbAdapter);
        $membershipCols = [];
        foreach (DbSelectColumns::MEMBERSHIP as $col) {
            $membershipCols[DbViewMembership::TABLE.'_'.$col] = $col;
        }
        $activityCols = [];
        foreach(DbSelectColumns::USER_ACTIVITY as $col) {
            $activityCols[DbViewUserActivity::TABLE.'_'.$col] = $col;
        }
        $userCols = [];
        foreach(DbSelectColumns::USER as $col) {
            $userCols[DbViewUsers::TABLE.'_'.$col] = $col;
        }        
        $select = $sql->select()
                ->from(['u' => DbViewUsers::TABLE])
                ->columns($userCols)
                ->join(['a' => DbViewUserActivity::TABLE], 'u.'.DbViewUsers::USERID.' = a.'.DbViewUserActivity::USERID, $activityCols)
                ->join(['m' => DbViewMembership::TABLE], 'm.'.DbViewMembership::USERID.' = u.'.DbViewUsers::USERID.' AND m.'.DbViewMembership::SITEID.' = a.'.DbViewUserActivity::SITEID, $membershipCols, Select::JOIN_LEFT)
                ->where($conditions);
        if (is_array($order)) {
            $this->orderSelect($select, $order);            
        }        
        if ($paginated) {
            return $this->getPaginator($select, false, $this->userSiteHydrator, $this->objectPrototype);
        }
        return $this->fetchResultSet($sql, $select, $this->userSiteHydrator, $this->objectPrototype);
    }
    
    /**
     * {@inheritDoc}
     */    
    public function findUsersOfSite($siteId, $order = null, $paginated = false)
    {
        return $this->findUsersOfSiteInternal($this->getUsersOfSiteConditions($siteId), $order, $paginated);
    }
    
    /**
     * {@inheritDoc}
     */        
    public function findUsersOfSiteByName($siteId, $query, $order = null, $paginated = false)
    {
        $conditions = $this->getUsersOfSiteConditions($siteId);
        $needle = sprintf('%%%s%%', mb_strtolower($query));            
        $conditions[sprintf("LOWER(u.%s) LIKE ?", DbViewUsers::DISPLAYNAME)] = $needle;
        if ($order === null) {
            $len = strlen($query);
            $order = [sprintf("ABS(LENGTH(u.%s) - $len)", DbViewUsers::DISPLAYNAME) => Order::ASCENDING];
        }
        return $this->findUsersOfSiteInternal($conditions, $order, $paginated);
    }
}
