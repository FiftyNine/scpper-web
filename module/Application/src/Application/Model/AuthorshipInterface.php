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
interface AuthorshipInterface 
{
    /**
     * @return UserInterface
     */
    public function getUser();
    
    /**
     * @return PageInterface
     */
    public function getPage();
    
    /**
     * @return int A constant from Application\Utils\AuthorRole;
     */
    public function getRole();
}
