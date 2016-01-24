<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Component\PaginatedTable;

/**
 * Description of Columns
 *
 * @author Alexander
 */
class Columns implements \IteratorAggregate
{
    /**
     * @var \Application\Component\PaginatedTable\Column[]
     */
    protected $columns;
        
    /**
     * @var int
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
        return new \ArrayIterator($this->columns);
    }
    
    /**
     * 
     * @param mixed $columns Associative array of column names => descriptions or array of Column objects
     */
    public function __construct($columns) 
    {
        if (!is_array($columns)) {
            return;
        }
        foreach ($columns as $key => $value) {
            if (is_string($key) && is_string($value)) {
                $this->addColumn($key, $value);
            } elseif ($value instanceof Column) {
                $this->columns[] = $value;
            }
        }
    }
    
    /**
     * 
     * @return int
     */
    public function getCount()
    {
        return count($this->columns);
    }
    
    /**
     * 
     * @return \Application\Component\PaginatedTable\Column
     * @throws \InvalidArgumentException
     */
    public function getColumn($index)
    {
        if (!is_int($index) || ($index < 0) || ($index >= $this->getCount())) {
            throw new \InvalidArgumentException('Invalid column index');
        }
        return $this->columns[$index];
    }

    /**
     * Add a column to the list
     * @param string $name
     * @param string $description
     * @return \Application\Component\PaginatedTable\Column
     */
    public function addColumn($name, $description)
    {
        return $this->columns[] = new Column($name, $description);        
    }    
    
    /**
     * Index of the column used to order rows in the table
     * @return int
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
     * @param int|string $column Index or name of the column to order rows by
     * @param bool $ascending
     */
    public function setOrder($column, $ascending = true)
    {
        if (is_string($column)) {
            $column = strtoupper($column);
            foreach ($this->columns as $index => $col) {
                if (strtoupper($col->getName()) === $column) {
                    $column = $index;
                    break;
                }
            }
        }
        $this->orderBy = (int)$column;
        $this->ascending = (bool)$ascending;
    }
}
