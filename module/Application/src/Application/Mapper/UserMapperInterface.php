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
interface UserMapperInterface extends SimpleMapperInterface
{
    /**
     * Returns a list of all users who are members of site or has any kind of activity on the site
     * @param int $siteId
     * @param string $query
     * @param array[string]int $order
     * @param bool $paginated
     * @return UserInterface[]|Paginator
     */
    public function findUsersOfSiteByName($siteId, $query, $order = null, $paginated = false);
    
    /**
     * Returns a list of all users who are members of site or has any kind of activity on the site
     * @param int $siteId
     * @param array[string]int $order
     * @param bool $paginated
     * @return UserInterface[]|Paginator
     */
    public function findUsersOfSite($siteId, $order = null, $paginated = false);    
}
