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
     * 
     * @return \DateTime
     */
    public function getCreationDate();
}
