<?php

namespace Application\Model;

use Application\Mapper\PageMapperInterface;
use Application\Mapper\AuthorshipMapperInterface;
use Application\Mapper\RevisionMapperInterface;
use Application\Mapper\VoteMapperInterface;

class Page implements PageInterface
{
    /**
     *
     * @var \Application\Mapper\PageMapperInterface
     */
    protected $pageMapper;    
    
    /**
     *
     * @var \Application\Mapper\AuthorMapperInterface
     */
    protected $authorMapper;

    /**
     *
     * @var \Application\Mapper\RevisionMapperInterface
     */
    protected $revisionMapper;
    
    /**
     *
     * @var \Application\Mapper\VoteMapperInterface
     */
    protected $voteMapper;    
    
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
     * 
     * @var RevisionInterface[]
     */
    protected $revisions;

    /**
     *
     * @var int
     */        
    protected $voteCount;

    /**
     * 
     * @var VoteInterface[]
     */
    protected $votes;

    /**
     *
     * @var AuthorshipInterface[]
     */        
    protected $authors;
    
    /**
     * @var int
     */
    protected $status;
    
    /**
     *
     * @var int
     */
    protected $originalId;

    /**
     *
     * @var PageInterface
     */
    protected $original;

    /**
     * Constructor
     * 
     * @param Application\Mapper\UserMapperInterface $userMapper
     */
    public function __construct(
            PageMapperInterface $pageMapper,
            AuthorshipMapperInterface $authorMapper, 
            RevisionMapperInterface $revisionMapper,
            VoteMapperInterface $voteMapper
    ) 
    {        
        $this->pageMapper = $pageMapper;
        $this->authorMapper = $authorMapper;
        $this->revisionMapper = $revisionMapper;
        $this->voteMapper = $voteMapper;
    }
    
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
        if (isset($this->revisions)) {
            return count($this->revisions);
        } else {
            return $this->revisionCount;
        }
    }
    
    public function setRevisionCount($value)
    {
        $this->revisionCount = $value;
    }

    public function getRevisions() 
    {
        /*if (!isset($this->revisions)) {
            $this->revisions = $this->revisionMapper->findRevisionsOfPage($this->getId());
        }*/
        return $this->revisions;
    }    
    
    /**
     * {@inheritDoc}
     */
    public function getAuthors() 
    {
        if (!isset($this->authors)) {
            $this->authors = $this->authorMapper->findAuthorshipsOfPage($this->getId());
        }
        return $this->authors;
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
    public function getVotes() 
    {
        /*if (!isset($this->votes)) {
            $this->votes = $this->voteMapper->findVotesOnPage($this);
        }*/
        return $this->revisions;        
    }

    /**
     * {@inheritDoc}
     */
    public function getOriginal() 
    {   
        if ($this->originalId === $this->getId()) {
            return $this;
        }
        if (!isset($this->original)) {
            $this->original = $this->pageMapper->find($this->originalId);
        }
        $this->original;
    }

    public function getOriginalId()
    {
        return $this->originalId;
    }
    
    public function setOriginalId($value)
    {
        $this->originalId = $value;
    }
    
    /**
     * {@inheritDoc}
     */
    public function getStatus() 
    {
        return $this->status;
    }
    
    public function setStatus($value)
    {
        $this->status = $value;
    }

}