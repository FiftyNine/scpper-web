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
}
