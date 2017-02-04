<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Component\PaginatedTable;

/**
 * Description of ColumnIterator
 *
 * @author Alexander
 */
class ColumnIterator extends \RecursiveArrayIterator 
{        
    public function hasChildren() 
    {
        return $this->current()->getSubColumns()->getCount() > 0;
    }
  
    public function getChildren() 
    {
        return $this->current()->getSubColumns()->getIterator();
    }  
}