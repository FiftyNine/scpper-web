<?php

namespace Application\Model;

use Application\Model\UserInterface;
use Application\Mapper\UserMapperInterface;

class Vote implements VoteInterface
{
    /**
     *
     * @var Application\Mapper\UserMapperInterface;
     */
    protected $userMapper;    
    
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
     * @var Application\Model\UserInterface
     */
    protected $user;
        
    /**
     * Constructor
     * @param UserMapperInterface $userMapper
     */    
    public function __construct(UserMapperInterface $userMapper)
    {
        $this->userMapper = $userMapper;
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
    
    
}
