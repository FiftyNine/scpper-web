<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Utils\Roundup;

/**
 * This is a wrapper class to provide a Page-like interface for raw data
 * to roundup html renderer.
 *
 * @author Alexander
 */
class PageView 
{
    private $pid;
    private $page;

    public function getTitle() {
        return $this->page['t'];
    }
    public function getAltTitle() {
        return $this->page['at'];
    }
    public function getUrl() {
        return "/page/".$this->pid;
    }
    public function getDeleted() {
        return $this->page['d'];
    }
    public function getName() {
        return $this->page['u'];
    }

    public function __construct($pageId, $pageData) {
        $this->pid = $pageId;
        $this->page= $pageData;
    }
} 