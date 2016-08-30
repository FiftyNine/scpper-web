<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Model;

/**
 * Description of Tag
 *
 * @author Alexander
 */
class Tag implements TagInterface
{
    /**
     *
     * @var string
     */
    protected $tag;
    
    /**
     *
     * @var int
     */
    protected $pageCount;
    
    /**
     * {@inheritDoc}
     */
    public function getTag()
    {
        return $this->tag;
    }

    /**
     * {@inheritDoc}
     */
    public function getPageCount()
    {
        return $this->pageCount;
    }
    
    public function setTag($tag)
    {
        $this->tag = $tag;
    }

    public function setPageCount($pageCount)
    {
        $this->pageCount = $pageCount;
    }
}
