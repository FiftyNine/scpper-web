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
use Application\Service\TagServiceInterface;
use Application\Service\UtilityServiceInterface;

/**
 * Description of HubService
 *
 * @author Alexander
 */
class HubService implements HubServiceInterface
{
    /**
     *
     * @var SiteServiceInterface
     */
    protected $siteService;
    
    /**
     *
     * @var UserServiceInterface
     */
    protected $userService;

    /**
     *
     * @var PageServiceInterface
     */
    protected $pageService;    

    /**
     *
     * @var RevisionServiceInterface
     */
    protected $revisionService;    

    /**
     *
     * @var VoteServiceInterface
     */
    protected $voteService;    

    /**
     *
     * @var TagServiceInterface
     */
    protected $tagService;    
    
    /**
     *
     * @var UtilityServiceInterface
     */
    protected $utilityService;

    public function __construct(
            SiteServiceInterface $siteService, 
            UserServiceInterface $userService,
            PageServiceInterface $pageService,
            RevisionServiceInterface $revisionService,
            VoteServiceInterface $voteService,
            TagServiceInterface $tagService,
            UtilityServiceInterface $utilityService
    ) 
    {
        $this->siteService = $siteService;
        $this->userService = $userService;
        $this->pageService = $pageService;
        $this->revisionService = $revisionService;
        $this->voteService = $voteService;
        $this->tagService = $tagService;
        $this->utilityService = $utilityService;        
    }
    
    /**
     * @return SiteServiceInterface
     */
    public function getSiteService()
    {
        return $this->siteService;
    }

    /**
     * @return UserServiceInterface
     */    
    public function getUserService()
    {
        return $this->userService;
    }
    
    /**
     * @return PageServiceInterface
     */    
    public function getPageService()
    {
        return $this->pageService;
    }
    
    /**
     * @return RevisionServiceInterface
     */    
    public function getRevisionService()
    {
        return $this->revisionService;
    }
    
    /**
     * @return VoteServiceInterface
     */    
    public function getVoteService()
    {
        return $this->voteService;
    }

    /**
     * @return TagServiceInterface
     */    
    public function getTagService()
    {
        return $this->tagService;
    }
    
    /**
     * @return UtilityServiceInterface
     */    
    public function getUtilityService()
    {
        return $this->utilityService;
    }
    
}
