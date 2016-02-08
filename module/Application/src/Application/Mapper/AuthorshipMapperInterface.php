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
interface AuthorshipMapperInterface extends SimpleMapperInterface
{
    /**
     * Returns all authorship entries for a specified page
     * @param int $pageId
     * @return \Application\Model\AuthorshipInterface[]
     */
    public function findAuthorshipsOfPage($pageId);
    
    /**
     * Returns all authorship entries for a specified user [on a certain site]
     * @param int $userId
     * @param int $siteId
     * @return \Application\Model\AuthorshipInterface[]
     */
    public function findAuthorshipsOfUser($userId, $siteId = -1);
    
    /**
     * Returns summary of information about user's authored pages
     * @param int $userId
     * @param int $siteId
     * @return \Application\Model\AuthorSummaryInterface
     */
    public function getAuthorSummary($userId, $siteId);
    
    /**
     * Returns user's rank (by total rating) on the specified wiki
     * @param int $userId
     * @param int $siteId
     * @return int
     */
    public function findUserRank($userId, $siteId);
    
    /**
     * Returns summary of information about site authors
     * @param int $siteId
     * @param array[string]int $order
     * @param bool $paginated
     * @return \Application\Model\AuthorSummaryInterface
     */
    public function getAuthorSummaries($siteId, $order = null, $paginated = false);
}
