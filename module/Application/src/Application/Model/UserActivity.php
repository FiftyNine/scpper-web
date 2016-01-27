<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Model;

use Application\Mapper\SimpleMapperInterface;
use Application\Mapper\AuthorshipMapperInterface;
use Application\Mapper\VoteMapperInterface;
use Application\Mapper\RevisionMapperInterface;
use Application\Utils\UserType;

/**
 * Description of UserActivity
 *
 * @author Alexander
 */
class UserActivity 
{
    /***** Data providers *****/
    
    /**
     * @var \Application\Mapper\UserMapperInterface;
     */
    protected $userMapper;
    
    /**
     * @var \Application\Mapper\SiteMapperInterface;
     */
    protected $siteMapper;
    
    /**
     * @var \Application\Mapper\VoteMapperInterface;
     */    
    protected $voteMapper;
    
    /**
     * @var \Application\Mapper\RevisionMapperInterface;
     */    
    protected $revisionMapper;
    
    /**
     * @var \Application\Mapper\AuthorMapperInterface;
     */    
    protected $authorMapper;
    
    /***** Model properties *****/
    
    /**
     * @var int
     */
    protected $siteId;
    
    /**
     * @var \Application\Model\SiteInterface
     */
    protected $site;
    
    /**
     * @var int
     */
    protected $userId;
    
    /**
     * @var \Application\Model\UserInterface
     */
    protected $user;
    
    /**
     * @var int
     */
    protected $voteCount;
    
    /**
     * @var \Application\Model\VoteInterface[]
     */    
    protected $votes;
    
    /**
     * @var int
     */    
    protected $revisionCount;
    
    /**
     * @var \Application\Model\RevisionInterface[]
     */    
    protected $revisions;
    
    /**
     *
     * @var int;
     */    
    protected $authorshipCount;
    
    /**
     * @var \Application\Model\AuthorshipInterface[]
     */    
    protected $authorships;
    
    /**
     * @var \DateTime
     */    
    protected $lastActivity;
    
    /***** Methods *****/
    
    /**
     * Constructor
     * @param SimpleMapperInterface $siteMapper
     * @param SimpleMapperInterface $userMapper
     * @param VoteMapperInterface $voteMapper
     * @param RevisionMapperInterface $revisionMapper
     * @param AuthorshipMapperInterface $authorMapper
     */
    public function __construct(
        SimpleMapperInterface $siteMapper,
        SimpleMapperInterface $userMapper,        
        VoteMapperInterface $voteMapper,
        RevisionMapperInterface $revisionMapper,
        AuthorshipMapperInterface $authorMapper
    ) 
    {
        $this->siteMapper = $siteMapper;
        $this->userMapper = $userMapper;
        $this->voteMapper = $voteMapper;
        $this->revisionMapper = $revisionMapper;
        $this->authorMapper = $authorMapper;        
    }

    /***** UserActivityInterface *****/
   
    /**
     * {@inheritDoc}
     */
    public function getUserId()
    {
        return $this->userId;
    }    
    
    /**
     * {@inheritDoc}
     */
    public function getUser()
    {
        if (!isset($this->user)) {
            $this->user = $this->userMapper->find($this->userId);
        }
        return $this->user;
    }

    /**
     * {@inheritDoc}
     */
    public function getSiteId()
    {
        return $this->userId;
    }
    
    /**
     * {@inheritDoc}
     */
    public function getSite()
    {
        if (!isset($this->site)) {
            $this->site = $this->siteMapper->find($this->siteId);
        }
        return $this->site;
    }
    
    /**
     * {@inheritDoc}
     */
    public function getVotes()
    {
        if (!isset($this->votes)) {
            $this->votes = $this->voteMapper->findVotesOfUser($this->userId, $this->siteId);
        }
        return $this->votes;            
    }
    
    /**
     * {@inheritDoc}
     */
    public function getVoteCount()
    {
        if (isset($this->votes)) {
            return count($this->votes);
        } else {
            return $this->voteCount;
        }
    }

    
    /**
     * {@inheritDoc}
     */
    public function getRevisions()
    {
        if (!isset($this->revisions)) {
            $this->revisions = $this->revisionMapper->findRevisionsByUser($this->userId, $this->siteId);
        }
        return $this->revisions;            
    }
    
    /**
     * {@inheritDoc}
     */
    public function getRevisionCount()
    {
        if (isset($this->revisions)) {
            return count($this->revisions);
        } else {
            return $this->revisionCount;
        }
    }
    
    /**
     * {@inheritDoc}
     */
    public function getAuthorships()
    {
        if (!isset($this->authorships)) {
            $this->authorships = $this->authorMapper->findAuthorshipsOfUser($this->userId, $this->siteId);
        }
        return $this->authorships;
    }
    
    /**
     * {@inheritDoc}
     */
    public function getAuthorshipCount()
    {
        if (isset($this->authorships)) {
            return count($this->authorships);
        } else {
            return $this->authorshipCount;
        }        
    }
    
    /**
     * {@inheritDoc}
     */
    public function getLastActivity()
    {
        return $this->lastActivity;
    }    
    
    /**
     * {@inheritDoc}
     */
    public function isActive()
    {
        if ($this->getLastActivity() === null) {
            return false;
        }
        $cutoff = new \DateTime();
        $interval = new \DateInterval(sprintf("P%dM", UserType::ACTIVITY_SPAN));
        $cutoff->sub($interval);
        return $this->getLastActivity() > $cutoff;
    }
    
    /***** Mutators *****/
    
    /**
     * 
     * @param int $userId
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
    }
    
    /**
     * @param int $siteId
     */
    public function setSiteId($siteId) 
    {
        $this->siteId = $siteId;
    }

    /**
     * @param int $voteCount
     */
    public function setVoteCount($voteCount) 
    {
        $this->voteCount = $voteCount;
    }

    /**
     * @param int $revisionCount
     */
    public function setRevisionCount($revisionCount) 
    {
        $this->revisionCount = $revisionCount;
    }

    /**
     * @param int $authorshipCount
     */
    public function setAuthorshipCount($authorshipCount) 
    {
        $this->authorshipCount = $authorshipCount;
    }    
    
    /**
     * @param \DateTime $lastActivity
     */
    public function setLastActivity(\DateTime $lastActivity = null) 
    {
        $this->lastActivity = $lastActivity;
    }    
}
