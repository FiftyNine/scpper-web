<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Model;

/**
 * Description of TagInterface
 *
 * @author Alexander
 */
interface TagInterface
{
    /**
     * @return string
     */
    public function getTag();
    
    /**
     * @return int
     */
    public function getPageCount();
}
