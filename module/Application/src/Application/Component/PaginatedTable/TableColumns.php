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
     * {@inheritDoc}
     */
    public function getIterator()
    {
        return new \RecursiveIteratorIterator(parent::getIterator());
    }
    
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
     * @param string $orderName Column->getOrderName() to order rows by
     * @param bool $ascending
     */
    public function setOrder($orderName, $ascending = true)
    {
        if (!is_string($orderName)) {
            return;
        }
        $orderName = strtoupper($orderName);
        foreach ($this->getIterator() as $col) {
            if (strtoupper($col->getOrderName()) === $orderName) {
                if ($col->canOrder()) {
                    $this->orderBy = $col;
                    $this->ascending = (bool)$ascending;            
                }                
                return;
            }
        }
    }    
}
