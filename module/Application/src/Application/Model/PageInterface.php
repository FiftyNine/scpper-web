<?php

namespace Application\Model;

interface PageInterface
{
    /**
     * @return int
     */
    public function getSiteId();
    
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
     * @return AuthorshipInterface[]
     */    
    public function getAuthors();
    
    /**
     * 
     * @return \DateTime
     */
    public function getCreationDate();
}
