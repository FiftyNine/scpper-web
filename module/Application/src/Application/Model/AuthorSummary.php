<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Model;

use Application\Model\UserInterface;

/**
 * Description of AuthorSummary
 *
 * @author Alexander
 */
class AuthorSummary implements AuthorSummaryInterface
{
    /**
     *
     * @var type @var int
     */
    protected $siteId;
    
    /**
     * @var int
     */
    protected $userId;    
    
    /**
     *
     * @var UserInterface
     */
    protected $user;
    
    /**
     * @var int
     */
    protected $pageCount;
    
    /**
     * @var int
     */
    protected $originalCount;
    
    /**
     * @var int
     */
    protected $scpCount;
    
    /**
     * @var int
     */
    protected $taleCount;
    
    /**
     * @var int
     */
    protected $jokeCount;
    
    /**
     * @var int
     */
    protected $goiCount;
    
    /**
     * @var int
     */
    protected $translationCount;
    
    /**
     * @var int
     */
    protected $totalRating;
    
    /**
     * @var int
     */
    protected $averageRating;
    
    /**
     * @var int
     */
    protected $highestRating;

    /**
     * @return int
     */
    function getSiteId()
    {
        return $this->siteId;
    }
       
    /**
     * @return int
     */
    function getUserId()
    {
        return $this->userId;
    }

    /**
     * @return UserInterface
     */
    public function getUser() 
    {
        return $this->user;
    }
    
    /**
     * @return int
     */
    function getPageCount()
    {
        return $this->pageCount;
    }
    
    /**
     * @return int
     */
    function getOriginalCount()
    {
        return $this->originalCount;
    }
    
    /**
     * @return int
     */
    function getTranslationCount()
    {
        return $this->translationCount;
    }
    
    public function getSCPCount()
    {
        return $this->scpCount;
    }

    public function getTaleCount()
    {
        return $this->taleCount;
    }

    public function getJokeCount()
    {
        return $this->jokeCount;
    }

    public function getGOICount()
    {
        return $this->goiCount;
    }
        
    /**
     * @return int
     */    
    function getTotalRating()
    {
        return $this->totalRating;
    }
    
    /**
     * @return int
     */    
    public function getAverageRating() 
    {
        return $this->averageRating;
    }
        
    /**
     * @return int
     */    
    function getHighestRating()
    {
        return $this->highestRating;
    }
    
    public function setSiteId($siteId) 
    {
        $this->siteId = $siteId;
    }

    public function setUserId($userId) 
    {
        $this->userId = $userId;
    }

    public function setUser($user) 
    {
        $this->user = $user;
    }    
    
    public function setPageCount($pageCount) 
    {
        $this->pageCount = $pageCount;
    }

    public function setOriginalCount($originalCount) 
    {
        $this->originalCount = $originalCount;
    }

    public function setTranslationCount($translationCount) 
    {
        $this->translationCount = $translationCount;
    }

    public function setSCPCount($scpCount)
    {
        $this->scpCount = $scpCount;
    }

    public function setTaleCount($taleCount)
    {
        $this->taleCount = $taleCount;
    }

    public function setJokeCount($jokeCount)
    {
        $this->jokeCount = $jokeCount;
    }

    public function setGOICount($goiCount)
    {
        $this->goiCount = $goiCount;
    }
        
    public function setTotalRating($totalRating) 
    {
        $this->totalRating = $totalRating;
    }
    
    public function setAverageRating($averageRating) 
    {
        $this->averageRating = $averageRating;
    }
    
    public function setHighestRating($highestRating) 
    {
        $this->highestRating = $highestRating;
    }
}
