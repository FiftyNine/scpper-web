<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Utils\Roundup;

/**
 * This is a wrapper class to provide a Authorship-like interface for raw data
 * to roundup html renderer.
 *
 * @author Alexander
 */
class AuthorView extends UserView {
    private $roleId;

    function getRole() {
        return $this->roleId;
    }
    function getUser() {
        return $this;
    }

    public function __construct($userId, $userData, $roleId) {
        parent::__construct($userId, $userData);
        $this->roleId = $roleId;
    }
}          