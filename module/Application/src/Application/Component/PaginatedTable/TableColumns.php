<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Component\PaginatedTable;

/**
 * List of tol-level columns of table
 *
 * @author Alexander
 */
class TableColumns extends ColumnList
{        
    /**
     * @var \Application\Component\PaginatedTable\Column
     */
    protected $orderBy;
    
    /**
     * @var bool
     */
    protected $ascending;

    /**
     * Column used to order rows in the table
     * @return \Application\Component\PaginatedTable\Column
     */
    public function getOrderBy()
    {
        return $this->orderBy;
    }
    
    /**
     * Is ordering ascending or descending
     * @return bool
     */
    public function isAscending()
    {
        return $this->ascending;
    }
    
    /**
     * Set new ordering rule
     * @param string $name Name of the column to order rows by
     * @param bool $ascending
     */
    public function setOrder($name, $ascending = true)
    {
        $column = $this->findColumn($name);
        if ($column && $column->canOrder()) {
            $this->orderBy = $column;
            $this->ascending = (bool)$ascending;            
        }
    }    
}
