<?php

namespace Application\Model;

class Site implements SiteInterface
{
    /**
     * 
     * @var int
     */
    protected $wikidotId;
    
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
     * @var DateTime
     */
    protected $lastUpdate;
            
    /**
     * 
     * {@inheritDoc}
     */
    public function getWikidotId()
    {
        return $this->wikidotId;
    }
    
    /**
     * 
     * @param int $wikidotId
     */
    public function setWikidotId($wikidotId)
    {
        $this->wikidotId = $wikidotId;
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
}

