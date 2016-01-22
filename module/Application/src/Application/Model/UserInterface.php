<?php

namespace Application\Model;

interface UserInterface
{
    /**
     * 
     * @return int
     */
    public function getId();
    
    /**
     * 
     * @return string
     */
    public function getName();
    
    /**
     * 
     * @return string
     */
    public function getDisplayName();
    
    /**
     * 
     * @return bool
     */
    public function getDeleted();
    
    /**
     * @return int
     */
    public function getVoteCount();
    
    /**
     * @return int
     */
    public function getRevisionCount();    
    
    /**
     * @return int
     */
    public function getPageCount();    
    
    /**
     * 
     * @return array()
     */
    public function getMembership();
    
    /**
     * Adds a membership to the list
     * @param int $siteId 
     * @param \DateTime|string $joinDate Date of joining or string formatted as 'Y-m-d'
     */
    public function addMembership($siteId, $joinDate);
}
