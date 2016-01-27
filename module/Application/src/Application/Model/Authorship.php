<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Model;

use Application\Mapper\SimpleMapperInterface;
use Application\Mapper\PageMapperInterface;

/**
 * Description of Authorship
 *
 * @author Alexander
 */
class Authorship implements AuthorshipInterface
{
    /**
     * @var \Application\Mapper\PageMapperInterface 
     */
    protected $pageMapper;
    
    /**
     * @var \Application\Mapper\SimpleMapperInterface 
     */
    protected $userMapper;
    
    /**
     * @var int
     */
    protected $id;
    
    /**
     * @var int
     */
    protected $pageId;
    
    /**
     * @var \Application\Model\PageInterface
     */
    protected $page;
    
    /**
     * @var int
     */
    protected $userId;
    
    /**
     * @var \Application\Model\UserInterface
     */
    protected $user;    
    
    /**
     * @var int
     */
    protected $role;        
    
    /**
     * Constructor
     * @param SimpleMapperInterface $userMapper
     * @param PageMapperInterface $pageMapper
     */
    public function __construct(
            SimpleMapperInterface $userMapper, 
            PageMapperInterface $pageMapper
    ) 
    {
        $this->pageMapper = $pageMapper;
        $this->userMapper = $userMapper;
    }
    
    /**
     * {@inheritDoc}
     */
    public function getPage() 
    {
        if (!isset($this->page))  {
            $this->page = $this->pageMapper->find($this->pageId);
        }
        return $this->page;
    }

    /**
     * {@inheritDoc}
     */
    public function getUser() 
    {
        if (!isset($this->user))  {
            $this->user = $this->userMapper->find($this->userId);
        }
        return $this->user;
    }
    
    /**
     * {@inheritDoc}
     */
    public function getRole() 
    {
        return $this->role;
    }    
    
    public function setRole($value)
    {
        $this->role = $value;
    }
    
    public function getUserId()
    {
        return $this->userId;
    }
    
    public function setUserId($value)
    {
        $this->userId = $value;
    }
    
    public function getPageId()
    {
        return $this->pageId;
    }
    
    public function setPageId($value)
    {
        $this->pageId = $value;
    }
}
