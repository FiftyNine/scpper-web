<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Mapper;

/**
 *
 * @author Alexander
 */
interface TagMapperInterface extends SimpleMapperInterface
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
     * @param bool $paginated Return a paginator
     * @return \Application\Model\TagInterface[]
     */
    public function findSiteTags($siteId, $paginated);

    /**
     * Find page tags
     * @param $pageId
     * @return \Application\Model\TagInterface[]
     */
    public function findPageTags($pageId);    
    
    /**
     * Return a tag object for hte specified site
     * @param int $siteId Id of a site   
     * @param string $tag Tag
     * @return \Application\Model\TagInterface
     */
    public function findTag($siteId, $tag);
}
