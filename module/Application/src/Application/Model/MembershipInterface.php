<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Model;

/**
 *
 * @author Alexander
 */
interface MembershipInterface 
{
    /**
     * @return int
     */
    public function getUserId();
    
    /**
     * @return \Application\Model\UserInterface
     */
    public function getUser();
    
    /**
     * @return int
     */
    public function getSiteId();
    
    /**
     * @return \Application\Model\SiteInterface
     */
    public function getSite();

    /**
     * @return \Application\Model\UserActivityInterface
     */
    public function getActivity();
    
    /**
     * @return \DateTime
     */
    public function getJoinDate();    
}
