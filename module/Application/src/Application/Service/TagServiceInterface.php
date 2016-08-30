<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Service;

/**
 * Description of TagServiceInterface
 *
 * @author Alexander
 */
interface TagServiceInterface
{        
    /**
     * Returns number of site pages
     * @param int $siteId Id of a site
     * @return int
     */
    public function countSiteTags($siteId);
    
    /**
     * Returns site pages
     * @param int $siteId Id of a site     
     * @return \Zend\Paginator\Paginator
     */
    public function findSiteTags($siteId);

    /**
     * Return a tag object for hte specified site
     * @param int $siteId Id of a site   
     * @param string $tag Tag
     * @return \Application\Model\TagInterface
     */
    public function find($siteId, $tag);
}
