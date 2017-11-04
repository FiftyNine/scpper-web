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
     * @return string
     */
    public function getAltTitle();
    
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
     * @return double
     */
    public function getWilsonScore();
    
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
     * @return TagInterface[]
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
     * @return int
     */
    public function getKind();
    
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
    
    /**
     * @return string
     */
    public function getUrl();
    
    /**
     * Returns an associative array of an object fields, 
     * easily convertable into json or other human-readable format
     * @return [... => ...]
     */
    public function toArray();
}
