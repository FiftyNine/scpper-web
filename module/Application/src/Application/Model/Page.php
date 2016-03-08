<?php

namespace Application\Model;

use Application\Mapper\SiteMapperInterface;
use Application\Mapper\PageMapperInterface;
use Application\Mapper\AuthorshipMapperInterface;
use Application\Mapper\RevisionMapperInterface;
use Application\Mapper\VoteMapperInterface;

class Page implements PageInterface
{
    /**
     * @var \Application\Mapper\SiteMapperInterface
     */
    protected $siteMapper;
    
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
     * @var \Application\Model\SiteInterface
     */
    protected $site;
    
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
    protected $contributorRating;
    
    /**
     *
     * @var int
     */    
    protected $adjustedRating;
    
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
     * @var PageInterface[]
     */
    protected $translations;
 
    /**
     *
     * @var int
     */
    protected $rank;
    
    /**
     * @var array[string]
     */
    protected $tags;
    
    /**
     * Constructor
     * @param ЫшеуMapperInterface $siteMapper
     * @param PageMapperInterface $pageMapper
     * @param AuthorshipMapperInterface $authorMapper
     * @param RevisionMapperInterface $revisionMapper
     * @param VoteMapperInterface $voteMapper
     */
    public function __construct(
            SiteMapperInterface $siteMapper,
            PageMapperInterface $pageMapper,
            AuthorshipMapperInterface $authorMapper, 
            RevisionMapperInterface $revisionMapper,
            VoteMapperInterface $voteMapper
    ) 
    { 
        $this->siteMapper = $siteMapper;
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
    public function getSite() 
    {
        if (!isset($this->site)) {
            $this->site = $this->siteMapper->find($this->getSiteId());
        }
        return $this->site;
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
    public function getContributorRating() 
    {
        return $this->contributorRating;
    }

    public function setContributorRating($contributorRating) 
    {
        $this->contributorRating = $contributorRating;
    }

    /**
     * {@inheritDoc}
     */
    public function getAdjustedRating() 
    {
        return $this->adjustedRating;
    }

    public function setAdjustedRating($adjustedRating) 
    {
        $this->adjustedRating = $adjustedRating;
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
        if (!isset($this->votes)) {
            $this->votes = $this->voteMapper->findVotesOnPage($this->getId());
        }
        return $this->votes;        
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
            if (!$this->originalId) {
                return null;
            }
            $this->original = $this->pageMapper->find($this->originalId);
        }
        return $this->original;
    }

    /**
     * {@inheritDoc}
     */
    public function getTranslations()
    {
       if (!isset($this->translations)) {
           $this->translations = $this->pageMapper->findTranslations($this->getId());
       }
       return $this->translations;
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

    /**
     * {@inheritDoc}
     */
    public function getRank()
    {
        if (!isset($this->rank)) {
            $this->rank = $this->pageMapper->findPageRank($this->getId());
        }
        return $this->rank;
    }

    public function setRank($rank)
    {
        $this->rank = $rank;
    }

    /**
     * {@inheritdoc}
     */
    public function getTags()
    {
        if (!isset($this->tags)) {
            $this->tags = $this->pageMapper->findPageTags($this->getId());
        }
        return $this->tags;
    }

    /**
     * {@inheritdoc}
     */
    public function getUrl()
    {
        return sprintf('/page/%d', $this->getId());
    }
}