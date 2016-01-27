<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Mapper;

/**
 *
 * @author Alexander
 */
interface UserActivityMapperInterface extends SimpleMapperInterface
{
    /**
     * Returns user activity information for a specified user and site
     * @param int $userId
     * @param int $siteId
     * @return \Application\Model\UserActivtyInterface
     * @throws \InvalidArgumentException
     */
    public function findUserActivity($userId, $siteId);
    
    /**
     * Returns activities information for a specified user on all sites
     * @param int $userId
     * @return \Application\Model\UserActivtyInterface[]
     */
    public function findUserActivities($userId);
}
