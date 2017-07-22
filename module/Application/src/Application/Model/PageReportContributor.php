<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Model;

/**
 * Description of PageReportContributor
 *
 * @author Alexander
 */
class PageReportContributor
{
    /**
     * @var int
     */
    protected $userId;
    
    /**
     * @var string
     */
    protected $userName;
    
    /**
     * @var int
     */
    protected $role;
    
    public function getUserId()
    {
        return $this->userId;
    }

    public function getUserName()
    {
        return $this->userName;
    }

    public function getRole()
    {
        return $this->role;
    }

    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    public function setUserName($userName)
    {
        $this->userName = $userName;
    }

    public function setRole($role)
    {
        $this->role = $role;
    }
    
    public function isValid()
    {
        return !is_null($this->userId) 
            && !is_null($this->userName)
            && !is_null($this->role);
    }
}
