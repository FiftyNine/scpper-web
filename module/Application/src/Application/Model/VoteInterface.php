<?php

namespace Application\Model;

interface VoteInterface
{
    /**
     * @return int
     */
    public function getPageId();
    
    /**
     * @return Application\Model\PageInterface
     */
    public function getPage();
    
    /**
     * @return int
     */
    public function getUserId();
    
    /**
     * @return Application\Model\UserInterface
     */
    public function getUser();
    
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
    
    /**
     * Returns human-readable representation of the value
     * @return string
     */
    public function getValueString();
    
    /**
     * There are previous votes from the same user on the same page
     * @return bool
     */
    public function hasHistory();
    
    /**
     * Return history of votes by the same user on the same page
     * @return VoteInterface[]
     */
    public function getHistory();
}