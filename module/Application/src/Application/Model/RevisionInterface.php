<?php

namespace Application\Model;

interface RevisionInterface
{
    /**
     * @return int
     */
    public function getId();
    
    /**
     * @return int
     */
    public function getPageId();
    
    /**
     * @return int
     */
    public function getIndex();
    
    /**
     * @return int
     */
    public function getUserId();
    
    /**
     * @return \Application\Model\UserInterface;
     */
    public function getUser();
    
    /**
     * @return \DateTime
     */
    public function getDateTime();
    
    /**
     * @return string|null
     */
    public function getComments();
}
