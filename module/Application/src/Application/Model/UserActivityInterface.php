<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Model;

/**
 *
 * @author Alexander
 */
interface UserActivityInterface 
{
    /**
     * @return int
     */
    public function getUserId();
    
    /**
     * @return \Application\Model\UserInterface
     */
    public function getUser();
    
    /**
     * @return int
     */
    public function getSiteId();
    
    /**
     * @return \Application\Model\SiteInterface
     */
    public function getSite();
    
    /**
     * @return \Application\Model\VoteInterface[]
     */
    public function getVotes();
    
    /**
     * @return int
     */
    public function getVoteCount();
    
    /**
     * @return \Application\Model\RevisionInterface[]
     */
    public function getRevisions();
    
    /**
     * @return int
     */
    public function getRevisionCount();
    
    /**
     * @return \Application\Model\AuthorshipInterface[]
     */
    public function getAuthorships();
    
    /**
     * @return int
     */
    public function getAuthorshipCount();
    
    /**
     * @return \DateTime
     */
    public function getLastActivity();
    
    /**
     * @return \Application\Model\AuthorSummaryInterface
     */
    public function getAuthorSummary();
    
    /**
     * @return boolean
     */
    public function isActive();
}
