<?php

namespace Application\Model;

interface PageInterface
{       
    /**
     * @return int
     */
    public function getSiteId();
 
    /**
     * @return Application\Model\SiteInterface
     */
    public function getSite();
    
    /**
     * @return int
     */
    public function getId();

    /**
     * @return string
     */
    public function getName();
    
    /**
     * @return string
     */
    public function getTitle();
    
    /**
     * @return int
     */
    public function getCategoryId();

    /**
     * @return int
     */
    public function getRating();
    
    /**
     * @return int
     */
    public function getCleanRating();
    
    /**
     * @return int
     */
    public function getContributorRating();
    
    /**
     * @return int
     */
    public function getAdjustedRating();
    
    /**
     * @return int
     */
    public function getRevisionCount();
    
    /**
     * @return RevisionInterface[]
     */
    public function getRevisions();
    
    /**
     * @return int
     */
    public function getVoteCount();
    
    /**
     * @return VoteInterface[]
     */
    public function getVotes();
    
    /**
     * @return array[string]
     */
    public function getTags();
    
    /**
     * @return AuthorshipInterface[]
     */    
    public function getAuthors();
    
    /**
     * 
     * @return \DateTime
     */
    public function getCreationDate();
    
    /**
     * @return int
     */
    public function getStatus();
    
    /**
     * @return PageInterface
     */
    public function getOriginal();
    
    /**
     * @return PageInterface[]
     */
    public function getTranslations();
    
    /**
     * @return int
     */
    public function getRank();
}
