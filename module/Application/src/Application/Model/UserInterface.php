<?php

namespace Application\Model;

use Application\Model\UserActivityInterface;
use Application\Model\MembershipInterface;

interface UserInterface
{
    /**
     * 
     * @return int
     */
    public function getId();
    
    /**
     * 
     * @return string
     */
    public function getName();
    
    /**
     * 
     * @return string
     */
    public function getDisplayName();
    
    /**
     * 
     * @return bool
     */
    public function getDeleted();
    
    /**
     * @return \Application\Model\UserActivityInterface[]
     */
    public function getActivities();
    
    /**
     * Returns a list of user activities on a specified site
     * @param int $siteId
     * @return \Application\Model\UserActivityInterface
     * @throws \InvalidArgumentException
     */
    public function getActivityOnSite($siteId);
    
    /**
     * Sets a list of activities for a specified site
     * @param \Application\Model\UserActivityInterface $activity
     */
    public function setActivity(UserActivityInterface $activity);
    
    /**
     * @return \Application\Model\MembershipInterface[]
     */
    public function getMemberships();
    
    /**
     * Returns information about membership on site or null if user is not a member
     * @param int $siteId
     * @return \Application\Model\MembershipInterface
     */
    public function getMembershipOfSite($siteId);    
    
    /**
     * @return string
     */
    public function getUrl();
    
    /**
     * Sets a membership for a specified site
     * @param \Application\Model\MembershipInterface $membership
     */
    public function setMembership(MembershipInterface $membership);
}