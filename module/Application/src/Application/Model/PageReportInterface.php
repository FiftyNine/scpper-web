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
     * 
     * @return \DateTime
     */
    public function getDate();    
    
    /**
     * @return Const of Application\Utils\PageStatus 
     */
    public function getStatus();

    /**
     * @return Const of Application\Utils\PageStatus 
     */
    public function getOldStatus();
    
    /**
     * @return int
     */    
    public function getOriginalId();
    
    /**
     * @return Const of Application\Utils\PageKind
     */
    public function getKind();

    /**
     * @return Const of Application\Utils\PageKind
     */
    public function getOldKind();
    
    /**
     * @return Application\Model\PageReportContributor[]
     */
    public function getContributors();

    /**
     * @return int A constant from Application\Utils\ReportState;
     */
    public function getReportState();
    
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