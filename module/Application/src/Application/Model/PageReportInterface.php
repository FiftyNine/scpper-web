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
interface PageReportInterface
{
    /**
     * @return int
     */    
    public function getId();
    
    /**
     * @return int
     */    
    public function getPageId();

    /**
     * @return string
     */    
    public function getReporter();
    
    /**
     * @return Const of Application\Utils\PageStatus 
     */
    public function getStatus();
    
    /**
     * @return int
     */    
    public function getOriginalId();
    
    /**
     * @return Const of Application\Utils\PageKind
     */
    public function getKind();
    
    /**
     * @return Application\Model\PageReportContributor[]
     */
    public function getContributors();

    /**
     * @return bool
     */
    public function getProcessed();
    
    /**
     * @return Application\Model\PageInterface
     */
    public function getPage();        
    
    /**
     * @return Application\Model\PageInterface
     */
    public function getOriginalPage();
    
    /**
     * @return string
     */
    public function getContributorsJson();
}