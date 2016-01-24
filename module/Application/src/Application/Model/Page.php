<?php

namespace Application\Model;

class Page implements PageInterface
{
    /**
     *
     * @var int
     */
    protected $siteId;
    
    /**
     *
     * @var int
     */    
    protected $id;
    
    /**
     *
     * @var string
     */    
    protected $name;
    
    /**
     *
     * @var string
     */    
    protected $title;
    
    /**
     *
     * @var int
     */    
    protected $categoryId;
    
    /**
     *
     * @var \DateTime
     */
    protected $creationDate;
    
    /**
     *
     * @var int
     */    
    protected $rating;
    
    /**
     *
     * @var int
     */    
    protected $cleanRating;
    
    /**
     *
     * @var int
     */        
    protected $revisionCount;
    
    /**
     * {@inheritDoc}
     */
    public function getSiteId()
    {
        return $this->siteId;
    }

    public function setSiteId($value)
    {
        $this->siteId = $value;
    }
    
    /**
     * {@inheritDoc}
     */
    public function getId()
    {
        return $this->id;
    }

    public function setId($value)
    {
        $this->id = $value;
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return $this->name;
    }
    
    public function setName($value)
    {
        $this->name = $value;
    }
    
    /**
     * {@inheritDoc}
     */
    public function getTitle()
    {
        return $this->title;
    }
    
    public function setTitle($value)
    {
        $this->title = $value;
    }
    
    /**
     * {@inheritDoc}
     */
    public function getCategoryId()
    {
        return $this->categoryId;
    }    

    public function setCategoryId()
    {
        return $this->categoryId;
    }
    
    /**
     * {@inheritDoc}
     */
    public function getCreationDate()
    {
        return $this->creationDate;
    }
    
    public function setCreationDate(\DateTime $value)
    {
        $this->creationDate = $value;
    }

    /**
     * {@inheritDoc}
     */
    public function getCleanRating() 
    {
        return $this->cleanRating;
    }

    public function setCleanRating($value)
    {
        $this->cleanRating = $value;
    }
    
    /**
     * {@inheritDoc}
     */
    public function getRating() 
    {
        return $this->rating;
    }

    public function setRating($value)
    {
        $this->rating = $value;
    }    
    
    /**
     * {@inheritDoc}
     */
    public function getRevisionCount() 
    {
        return $this->revisionCount;
    }
    
    public function setRevisionCount($value)
    {
        $this->revisionCount = $value;
    }    
}