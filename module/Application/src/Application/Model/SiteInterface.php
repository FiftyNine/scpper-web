<?php

namespace Application\Model;

interface SiteInterface
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
    public function getEnglishName();

    /**
     * 
     * @return string
     */
    public function getWikidotName();

    /**
     * 
     * @return string
     */
    public function getShortName();

    /**
     * 
     * @return string
     */
    public function getNativeName();    
    
    /**
     * 
     * @return \DateTime
     */
    public function getLastUpdate();  
    
    /**
     * 
     * @return string
     */
    public function getUrl();
    
    /**
     * 
     * @return int
     */
    public function getMembers();
    
    /**
     * 
     * @return int
     */    
    public function getActiveMembers();

    /**
     * 
     * @return int
     */    
    public function getContributors();
    
    /**
     * 
     * @return int
     */
    public function getAuthors();
    
    /**
     * 
     * @return int
     */
    public function getPages();
    
    /**
     * 
     * @return int
     */
    public function getOriginals();
    
    /**
     * 
     * @return int
     */
    public function getTranslations();
    
    /**
     * 
     * @return int
     */
    public function getVotes();
    
    /**
     * 
     * @return int
     */
    public function getPositive();
    
    /**
     * 
     * @return int
     */
    public function getNegative();
    
    /**
     * 
     * @return int
     */
    public function getRevisions();  
    
    /**
     * @return bool
     */
    public function getHideVotes();
}
