<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Service;

use Application\Service\SiteServiceInterface;
use Application\Service\UserServiceInterface;
use Application\Service\PageServiceInterface;
use Application\Service\RevisionServiceInterface;
use Application\Service\VoteServiceInterface;
use Application\Service\UtilityServiceInterface;


/**
 *
 * @author Alexander
 */
interface HubServiceInterface 
{
    /**
     * @return SiteServiceInterface
     */
    public function getSiteService();

    /**
     * @return UserServiceInterface
     */    
    public function getUserService();
    
    /**
     * @return PageServiceInterface
     */    
    public function getPageService();
    
    /**
     * @return RevisionServiceInterface
     */    
    public function getRevisionService();
    
    /**
     * @return VoteServiceInterface
     */    
    public function getVoteService();

    /**
     * @return TagServiceInterface
     */    
    public function getTagService();

    
    /**
     * @return UtilityServiceInterface
     */    
    public function getUtilityService();
}
