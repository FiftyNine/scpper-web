<?php

namespace Application\Model;

class Site implements SiteInterface
{
    /**
     * 
     * @var int
     */
    protected $id;
    
    /**
     *
     * @var string
     */
    protected $englishName;
    
    /**
     *
     * @var string
     */
    protected $wikidotName;

    /**
     *
     * @var string
     */
    protected $shortName;
    
    /**
     *
     * @var string
     */
    protected $nativeName;
    
    /**
     *
     * @var DateTime
     */
    protected $lastUpdate;

    /**
     * 
     * @var int
     */
    protected $members;

    /**
     * 
     * @var int
     */
    protected $activeMembers;
    
    /**
     * 
     * @var int
     */
    protected $contributors;
    
    /**
     * 
     * @var int
     */
    protected $authors;
    
    /**
     * 
     * @var int
     */
    protected $pages;
    
    /**
     * 
     * @var int
     */
    protected $originals;
    
    /**
     * 
     * @var int
     */
    protected $translations;
    
    /**
     * 
     * @var int
     */
    protected $votes;
    
    /**
     * 
     * @var int
     */
    protected $positive;
    
    
    /**
     * 
     * @var int
     */
    protected $negative;
    
    /**
     * 
     * @var int
     */
    protected $revisions;

    /**
     *
     * @var bool
     */
    protected $hideVotes;
    
    /**
     * 
     * {@inheritDoc}
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * 
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }
    
    /**
     * 
     * {@inheritDoc}
     */
    public function getEnglishName()
    {
        return $this->englishName;
    }
    
    /**
     * 
     * @param string $englishName
     */
    public function setEnglishName($englishName)
    {
        $this->englishName = $englishName;
    }

    /**
     * 
     * {@inheritDoc}
     */
    public function getWikidotName()
    {
        return $this->wikidotName;
    }
    
    /**
     * 
     * @param string $wikidotName
     */
    public function setWikidotName($wikidotName)
    {
        $this->wikidotName = $wikidotName;
    }    

    /**
     * 
     * {@inheritDoc}
     */
    public function getShortName()
    {
        return $this->shortName;
    }
    
    /**
     * 
     * @param string $shortName
     */
    public function setShortName($shortName)
    {
        $this->shortName = $shortName;
    }   

    /**
     * 
     * {@inheritDoc}
     */
    public function getNativeName()
    {
        return $this->nativeName;
    }
    
    /**
     * 
     * @param string $nativeName
     */
    public function setNativeName($nativeName)
    {
        $this->nativeName = $nativeName;
    }   
    
    /**
     * 
     * {@inheritDoc}
     */
    public function getLastUpdate()
    {
        return $this->lastUpdate;
    }
    
    /**
     * 
     * @param \DateTime|string $lastUpdate
     */
    public function setLastUpdate($lastUpdate)
    {
        if ($lastUpdate instanceof \DateTime) {
            $this->lastUpdate = $lastUpdate;
        }
        else if (is_string($lastUpdate)) {
            $this->lastUpdate = new \DateTime($lastUpdate);
        } else if (is_integer($lastUpdate)) {
            $this->lastUpdate = new \DateTime();
            $this->lastUpdate->setTimeStamp($lastUpdate);
        }
    }        
    
    /**
     * {@inheritDoc}
     */
    public function getUrl()
    {
        return "http://{$this->getWikidotName()}.wikidot.com";
    }

    /**
     * {@inheritDoc}
     */
    public function getActiveMembers()
    {
        return $this->activeMembers;
    }

    /**
     * {@inheritDoc}
     */
    public function getAuthors()
    {
        return $this->authors;
    }

    /**
     * {@inheritDoc}
     */
    public function getContributors()
    {
        return $this->contributors;
    }

    /**
     * {@inheritDoc}
     */
    public function getMembers()
    {
        return $this->members;
    }

    /**
     * {@inheritDoc}
     */
    public function getNegative()
    {
        return $this->negative;
    }

    /**
     * {@inheritDoc}
     */
    public function getOriginals()
    {
        return $this->originals;
    }

    /**
     * {@inheritDoc}
     */
    public function getPages()
    {
        return $this->pages;
    }

    /**
     * {@inheritDoc}
     */
    public function getPositive()
    {
        return $this->positive;
    }

    /**
     * {@inheritDoc}
     */
    public function getRevisions()
    {
        return $this->revisions;
    }

    /**
     * {@inheritDoc}
     */
    public function getTranslations()
    {
        return $this->translations;
    }

    /**
     * {@inheritDoc}
     */
    public function getVotes()
    {
        return $this->votes;
    }
    
    /**
     * {@inheritDoc}
     */
    public function getHideVotes()
    {
        return $this->hideVotes;
    }
    
    public function setMembers($members)
    {
        $this->members = $members;
    }

    public function setActiveMembers($activeMembers)
    {
        $this->activeMembers = $activeMembers;
    }

    public function setContributors($contributors)
    {
        $this->contributors = $contributors;
    }

    public function setAuthors($authors)
    {
        $this->authors = $authors;
    }

    public function setPages($pages)
    {
        $this->pages = $pages;
    }

    public function setOriginals($originals)
    {
        $this->originals = $originals;
    }

    public function setTranslations($translations)
    {
        $this->translations = $translations;
    }

    public function setVotes($votes)
    {
        $this->votes = $votes;
    }

    public function setPositive($positive)
    {
        $this->positive = $positive;
    }

    public function setNegative($negative)
    {
        $this->negative = $negative;
    }

    public function setRevisions($revisions)
    {
        $this->revisions = $revisions;
    }    
    
    public function setHideVotes($hideVotes)
    {
        $this->hideVotes = $hideVotes;
    }    
}

