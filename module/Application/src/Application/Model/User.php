<?php

namespace Application\Model;

use Application\Model\UserInterface;
use Application\Mapper\UserActivityMapperInterface;
use Application\Mapper\MembershipMapperInterface;

class User implements UserInterface
{    
    /**
     *
     * @var \Application\Mapper\UserActivityMapperInterface
     */
    protected $activityMapper;

    /**
     *
     * @var \Application\Mapper\MembershipMapperInterface
     */
    protected $membershipMapper;
    
    /**
     * @var int
     */
    protected $id;
    
    /**
     * @var string
     */
    protected $name;
    
    /**
     * @var string
     */
    protected $displayName;
    
    /**
     * @var bool
     */
    protected $deleted;  
    
    /**
     * An associative array of [SiteId => UserActivityInterface]
     * @var array[int]\Application\Model\UserActivityInterface
     */
    protected $activitiesBySite = array();
    
    /**
     * @var UserActivityInterface[] 
     */
    protected $activities;

    /**
     * An associative array of [SiteId => MembershipInterface]
     * @var array[int]\Application\Model\MembershipInterface
     */
    protected $membershipsBySite = array();
    
    /**
     * @var MembershipInterface[] 
     */
    protected $memberships;
    
    /**
     * Constructor
     * @param UserActivityMapperInterface $activityMapper
     */
    public function __construct(
            UserActivityMapperInterface $activityMapper,
            MembershipMapperInterface $membershipMapper
    ) 
    {
        $this->activityMapper = $activityMapper;
        $this->membershipMapper = $membershipMapper;
    }
    
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
    public function getActivities()
    {
        if (!isset($this->activities)) {
            $this->activities = $this->activityMapper->findUserActivities($this->getId());
            foreach ($this->activities as $activity) {                
                $this->activitiesBySite[$activity->getSiteId()] = $activity;
            }            
        }
        return $this->activities;
    }
    
    /**     
     * {@inheritDoc}
     */
    public function getActivityOnSite($siteId)
    {
        if (!array_key_exists($siteId, $this->activitiesBySite)) {
            $this->activitiesBySite[$siteId] = $this->activityMapper->findUserActivity($this->getId(), $siteId);
        }
        return $this->activitiesBySite[$siteId];
    }
    
    /**
     * {@inheritdoc}
     */
    public function setActivity(UserActivityInterface $activity) 
    {
        $this->activitiesBySite[$activity->getSiteId()] = $activity;
    }
    
    /**     
     * {@inheritDoc}
     */
    public function getMemberships()
    {
        if (!isset($this->memberships)) {
            $this->memberships = $this->membershipMapper->findMembershipsOfUser($this->getId());
            foreach ($this->memberships as $membership) {
                $this->membershipsBySite[$membership->getSiteId()] = $membership;
            }            
        }
        return $this->memberships;
    }

    /**     
     * {@inheritDoc}
     */
    public function getMembershipOfSite($siteId) 
    {
/*        if (!array_key_exists($siteId, $this->membershipsBySite)) {
            if (!isset($this->memberships)) {
                $this->getMemberships();
            }
        }*/
        if (array_key_exists($siteId, $this->membershipsBySite)) {            
            return $this->membershipsBySite[$siteId];
        } else {        
            return null;
        }
    }
    
    /**     
     * {@inheritDoc}
     */    
    public function setMembership(MembershipInterface $membership) 
    {
        $this->membershipsBySite[$membership->getSiteId()] = $membership;
    }

}