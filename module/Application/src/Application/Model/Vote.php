<?php

namespace Application\Model;

use Application\Model\UserInterface;
use Application\Mapper\UserMapperInterface;
use Application\Mapper\PageMapperInterface;
use Application\Mapper\VoteMapperInterface;

class Vote implements VoteInterface
{
    /**
     *
     * @var Application\Mapper\UserMapperInterface;
     */
    protected $userMapper;    

    /**
     *
     * @var Application\Mapper\PageMapperInterface;
     */
    protected $pageMapper;

    /**
     *
     * @var Application\Mapper\VoteMapperInterface;
     */
    protected $voteMapper;
    
    /**
     *
     * @var \DateTime
     */
    protected $dateTime;
    
    /**
     *
     * @var int
     */
    protected $pageId;
    
    /**
     *
     * @var int
     */
    protected $userId;
    
    /**
     *
     * @var int
     */
    protected $value;
    
    /**
     *
     * @var bool
     */
    protected $fromMember;
    
    /**
     *
     * @var bool
     */
    protected $fromContributor;
    
    /**
     *
     * @var bool
     */
    protected $fromActive;

    /**
     * 
     * @var bool
     */
    protected $hasHistory;
    
    /**
     *
     * @var Application\Model\PageInterface
     */
    protected $page;
    
    /**
     *
     * @var Application\Model\UserInterface
     */
    protected $user;
       
    
    /**
     * 
     * @var Application\Model\VoteInterface[]
     */
    protected $history;
    
    /**
     * Constructor
     * @param UserMapperInterface $userMapper
     * @param PageMapperInterface $pageMapper
     */    
    public function __construct(
            UserMapperInterface $userMapper,
            PageMapperInterface $pageMapper,
            VoteMapperInterface $voteMapper)
    {
        $this->userMapper = $userMapper;
        $this->pageMapper = $pageMapper;
        $this->voteMapper = $voteMapper;
    }
    
    /**
     * {@inheritdoc}
     */
    public function getDateTime()
    {    
        return $this->dateTime;
    }

    /**
     * {@inheritdoc}
     */
    public function getPageId()
    {    
        return $this->pageId;
    }

    /**
     * {@inheritdoc}
     */
    public function getPage()
    {
        if (!isset($this->page)) {
            $this->page = $this->pageMapper->find($this->getPageId());
        }
        return $this->page;
    }
    
    /**
     * {@inheritdoc}
     */
    public function getUserId()
    {    
        return $this->userId;
    }

    /**
     * {@inheritdoc}
     */
    public function getUser()
    {
        if (!isset($this->user)) {
            $this->user = $this->userMapper->find($this->getUserId());
        }
        return $this->user;
    }
        
    /**
     * {@inheritdoc}
     */
    public function getValue()            
    {    
        return $this->value;
    }
    
    /**
     * {@inheritdoc}
     */
    public function getFromMember()
    {
        return $this->fromMember;
    }

    /**
     * {@inheritdoc}
     */
    public function getFromContributor()
    {
        return $this->fromContributor;
    }

    /**
     * {@inheritdoc}
     */    
    public function getFromActive()
    {
        return $this->fromActive;
    }

    /**
     * {@inheritdoc}
     */    
    public function getValueString()
    {
        if ($this->value > 0) {
            return '+';
        } else if ($this->value < 0) {
            return '-';
        } else {
            return '&Oslash;';
        }
    }
    
    /**
     * {@inheritdoc}
     */    
    public function hasHistory()
    {
        return $this->hasHistory;
    }

    /**
     * {@inheritdoc}
     */    
    public function getHistory()
    {
        if (!isset($this->history)) {
            $this->history = $this->voteMapper->findHistory($this->getPageId(), $this->getUserId());
        }
        return $this->history;
    }
    
    public function setDateTime(\DateTime $dateTime)
    {
        $this->dateTime = $dateTime;
    }

    public function setPageId($pageId)
    {
        $this->pageId = $pageId;
    }

    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    public function setUser(UserInterface $user)
    {
        $this->user = $user;
    }
        
    public function setPage(PageInterface $page)
    {
        $this->page = $page;
    }

    public function setValue($value)
    {
        $this->value = $value;
    }

    public function setFromMember($fromMember)
    {
        $this->fromMember = $fromMember;
    }

    public function setFromContributor($fromContributor)
    {
        $this->fromContributor = $fromContributor;
    }

    public function setFromActive($fromActive)
    {
        $this->fromActive = $fromActive;
    }

    public function setHasHistory($hasHistory)
    {
        $this->hasHistory = $hasHistory;
    }    
    
}
