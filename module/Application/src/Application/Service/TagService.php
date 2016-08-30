<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Service;

use Application\Mapper\TagMapperInterface;

/**
 * Description of TagService
 *
 * @author Alexander
 */
class TagService implements TagServiceInterface
{
    /**
     * @var \Application\Mapper\TagMapperInterface
     */
    protected $mapper;

    /**
     * @param \Application\Mapper\TagMapperInterface $mapper
     */
    public function __construct(TagMapperInterface $mapper) 
    {
        $this->mapper = $mapper;
    }
    
    /**
     * {@inheritDoc}
     */
    public function countSiteTags($siteId)
    {
        return $this->mapper->countSiteTags($siteId);
    }

    /**
     * {@inheritDoc}
     */    
    public function find($siteId, $tag)
    {
        return $this->mapper->findTag($siteId, $tag);
    }

    /**
     * {@inheritDoc}
     */
    public function findSiteTags($siteId)
    {
        return $this->mapper->findSiteTags($siteId, false);
    }
}
