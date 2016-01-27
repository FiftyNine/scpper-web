<?php

namespace Application\Model;

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
     * @return \Application\Model\MembershipInterface[]
     */
    public function getMemberships();
    
    /**
     * Returns information about membership on site or null if user is not a member
     * @param int $siteId
     * @return \Application\Model\UserActivityInterface
     */
    public function getMembershipOfSite($siteId);    
}