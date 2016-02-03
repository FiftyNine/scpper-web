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
interface AuthorSummaryInterface 
{
    /**
     * @return int
     */
    function getSiteId();
    
    /**
     * @return int
     */
    function getUserId();
    
    /**
     * @return Application\Model\UserInterface
     */
    function getUser();
    
    /**
     * @return int
     */
    function getPageCount();
    
    /**
     * @return int
     */
    function getOriginalCount();
    
    /**
     * @return int
     */
    function getTranslationCount();
    
    /**
     * @return int
     */    
    function getTotalRating();

    /**
     * @return int
     */    
    function getAverageRating();
    
    /**
     * @return int
     */    
    function getHighestRating();
}
