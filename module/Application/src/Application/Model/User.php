<?php

namespace Application\Model;

use Application\Model\UserInterface;

class User implements UserInterface
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
    protected $name;
    
    /**
     *
     * @var string
     */
    protected $displayName;
    
    /**
     *
     * @var bool
     */
    protected $deleted;    
    
    /**
     * @var array[SiteId => JoinDate]
     */
    protected $membership = array();
    
    /**
     * {@inheritDoc}
     */
    public function getDeleted() 
    {
        return $this->deleted;
    }

    public function setDeleted($value) 
    {
        $this->deleted = $value;
    }

    /**
     * {@inheritDoc}
     */
    public function getDisplayName() 
    {
        return $this->displayName;
    }

    public function setDisplayName($value) 
    {
        $this->displayName = $value;
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
    public function getMembership()
    {
        return $this->membership;
    }
       
    /**
     * {@inheritDoc}
     */
    public function addMembership($siteId, $joinDate)
    {    
        if (is_string($joinDate)) {
            $joinDate = \DateTime::createFromFormat('Y-m-d H:i:s', $joinDate);
        }
        if ($joinDate instanceof \DateTime) {
            $this->membership[$siteId] = $joinDate;
        }
    }
}