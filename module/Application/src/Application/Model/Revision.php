<?php

namespace Application\Model;

use Application\Mapper\UserMapperInterface;
use Application\Model\UserInterface;

class Revision implements RevisionInterface, \JsonSerializable
{
    /**
     *
     * @var Application\Mapper\UserMapperInterface;
     */
    protected $userMapper;
    
    /**
     * @var string
     */
    protected $comments;

    /**
     * @var \DateTime
     */
    protected $dateTime;

    /**
     * @var int
     */    
    protected $pageId;
    
    /**
     * @var int
     */    
    protected $id;
    
    /**
     * @var int
     */    
    protected $index;
    
    /**
     * @var int
     */    
    protected $userId;
    
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
    public function getComments()
    {
        return $this->comments;
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
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getIndex()
    {
        return $this->index;
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
    
    public function setUser(UserInterface $user)
    {
        $this->user = $user;
    }
    
    public function setComments($comments)
    {
        $this->comments = $comments;
    }

    public function setDateTime(\DateTime $dateTime)
    {
        $this->dateTime = $dateTime;
    }

    public function setPageId($pageId)
    {
        $this->pageId = $pageId;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setIndex($index)
    {
        $this->index = $index;
    }

    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        return array(
            'index' => $this->getIndex(),
            //'date' => $this->getDateTime()->format(\DateTime::ISO8601),
            'comments' => $this->getComments(),
            'user' => array(
                'id' => $this->getUserId(),
                'name' => $this->getUser()->getName(),
                'displayName' => $this->getUser()->getDisplayName()
            )
        );
    }

}
