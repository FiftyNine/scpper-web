<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Model;

use Application\Mapper\SiteMapperInterface;
use Application\Mapper\UserMapperInterface;

/**
 * Description of Membership
 *
 * @author Alexander
 */
class Membership implements MembershipInterface
{
    /***** Mappers *****/
    
    /**
     * @var \Application\Mapper\UserMapperInterface
     */
    protected $userMapper;

    /**
     * @var \Application\Mapper\SiteMapperInterface
     */
    protected $siteMapper;
    
    /***** Model properties *****/
    
    /**
     * @var int
     */
    protected $userId;
    
    /**
     * @var UserInterface
     */
    protected $user;
    
    /**
     * @var int
     */
    protected $siteId;
    
    /**
     * @var SiteInterface
     */
    protected $site;
    
    /**
     * @var \DateTime
     */
    protected $joinDate;
    
    /**
     * Constructor
     * @param SiteMapperInterface $siteMapper
     * @param UserMapperInterface $userMapper
     */
    public function __construct(
        SiteMapperInterface $siteMapper,
        UserMapperInterface $userMapper
    ) 
    {
        $this->siteMapper = $siteMapper;
        $this->userMapper = $userMapper;
    }
    
    /***** MembershipInterface *****/
    
    /**
     * {@inheritdoc}
     */
    public function getActivity() 
    {
        return $this->getUser()->getActivityOnSite($this->getSiteId());
    }

    /**
     * {@inheritdoc}
     */
    public function getJoinDate()
    {
        return $this->joinDate;
    }

    /**
     * {@inheritdoc}
     */
    public function getSite() 
    {
        if (!isset($this->site)) {
            $this->site = $this->siteMapper->find($this->siteId);
        }
        return $this->site;
    }

    /**
     * {@inheritdoc}
     */
    public function getSiteId() 
    {
        return $this->siteId;
    }

    /**
     * {@inheritdoc}
     */
    public function getUser() 
    {
        if (!isset($this->user)) {
            $this->user = $this->userMapper->find($this->userId);
        }
        return $this->user;
    }

    /**
     * {@inheritdoc}
     */
    public function getUserId() 
    {
        return $this->userId;
    }
    
    /***** Mutators *****/
    
    /**
     * @param int $siteId
     */
    public function setSiteId($siteId)
    {
        $this->siteId = $siteId;
    }
    
    /**
     * @param int $userId
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
    }    
    
    /**
     * @param \DateTime $joinDate
     */
    public function setJoinDate(\DateTime $joinDate = null)
    {
        $this->joinDate = $joinDate;
    }    
}
