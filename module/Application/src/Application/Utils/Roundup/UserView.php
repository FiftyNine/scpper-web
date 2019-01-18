<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Utils\Roundup;

/**
 * This is a wrapper class to provide a User-like interface for raw data
 * to roundup html renderer.
 *
 * @author Alexander
 */
class UserView 
{
    private $uid;
    private $user;

    function getDisplayName() {
        return $this->user['dn'];
    }
    function getDeleted() {
        return $this->user['d'];
    }
    function getUrl() {
        return "/user/".$this->uid;
    }

    public function __construct($userId, $userData) {
        $this->uid = $userId;
        $this->user = $userData;
    }
}