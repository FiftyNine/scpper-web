<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Component\PaginatedTable;

/**
 *
 * @author Alexander
 */
interface TableInterface 
{
    /**
     * Get a list of columns for this table
     * @return Application\Component\PaginatedTable\Columns
     */
    public function getColumns();       
    
    /**
     * Get paginator
     * @return \Zend\Paginator\Paginator
     */
    public function getPaginator();
    
    /**
     * Get body view
     * @return string
     */
    public function getBodyView();
    
    /**
     * @return bool
     */
    public function isPreview();    
}
