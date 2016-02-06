<?php

namespace Application\Model;

class Revision implements RevisionInterface, \JsonSerializable
{
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
        );
    }

}
