<?php

namespace Application\Model;

interface VoteInterface
{
    /**
     * @return int
     */
    public function getPageId();
    
    /**
     * @return int
     */
    public function getUserId();
    
    /**
     * @return int
     */
    public function getValue();
    
    /**
     * @return \DateTime
     */    
    public function getDateTime();
    
    /**
     * Vote from a member of the site
     * @return bool
     */
    public function getFromMember();
    
    /**
     * Vote from an author of the site
     * @return bool
     */
    public function getFromContributor();
    
    /**
     * Vote from an active member of the site
     * @return bool
     */
    public function getFromActive();
}