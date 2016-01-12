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
     * 
     * @return \DateTime
     */
    public function getCreationDate();
}
