<?php

namespace Application\Model;

interface SiteInterface
{
    /**
     * 
     * @return int
     */
    public function getWikidotId();    
    
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
     * @return \DateTime
     */
    public function getLastUpdate();  
    
    /**
     * 
     * @return string
     */
    public function getUrl();
}
