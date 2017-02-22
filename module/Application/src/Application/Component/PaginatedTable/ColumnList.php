<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Component\PaginatedTable;

/**
 * Description of ColumnList
 *
 * @author Alexander
 */
class ColumnList implements \IteratorAggregate
{
    /**
     * @var \Application\Component\PaginatedTable\Column[]
     */
    protected $columns;
    
    /**
     * Checks if this list contains column
     * @param $column \Application\Component\PaginatedTable\Column Column to find
     * @param $recursive Whether to search in subcolumns
     * @return bool
     */
    protected function hasColumn($column, $recursive = true)
    {
        $res = (bool)array_search($this->columns, $column);
        if ($recursive) {
            foreach ($this->columns as $index => $col) {
                $res|=$col->getSubColumns()->hasColumn($column, true);
            }
        }
        return $res;
    }

    /**
     * Searches list for the specific column
     * @param string $name Column to find
     * @param bool $recursive Whether to search in subcolumns
     * @return \Application\Component\PaginatedTable\Column
     */
    public function findColumn($name, $recursive = true)
    {            
        if (is_string($name)) {
            $name = strtoupper($name);
        } else {
            return null;
        }
        $res = null;    
        foreach ($this->columns as $index => $col) {
            if (is_string($name) && strtoupper($col->getName()) === $name) {
                $res = $col;
            }
            if ($recursive && !$res) {
                $res = $col->getSubColumns()->findColumn($name, $recursive);
            }            
            if ($res) {
                break;
            }
        }        
        return $res;
    }
    
    /**
     * {@inheritDoc}
     */
    public function getIterator()
    {
        return new ColumnIterator($this->columns);
    }
    
    /**
     * 
     * @param mixed $columns Associative array of column names => descriptions or array of Column objects
     */
    public function __construct($columns) 
    {
        $this->columns = [];
        if (!is_array($columns)) {
            return;
        }
        foreach ($columns as $col) {
            if ($col instanceof Column) {
                $this->columns[] = $col;
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
    public function addColumn($name, $orderName)
    {
        return $this->columns[] = new Column($name, $orderName);        
    }    
       
    /**
     * Returns highest nested level for this group of columns
     * @return int
     */
    public function getMaxNestedLevel()
    {
        $res = 0;
        if ($this->getCount() > 0) {
            $res = 1;
        }
        $maxChild = 0;
        foreach ($this->columns as $index => $column) {
            $maxChild = max($maxChild, $column->getSubColumns()->getMaxNestedLevel());
        }
        $res+=$maxChild;
        return $res;
    }
    
    public function toArray()
    {
        $res = [];
        if ($this->getCount() > 0) {
            $res[] = [];
            foreach ($this->columns as $index => $column) {
                $res[0][] = $column;
                $subArray = $column->getSubColumns()->toArray();
                for ($i=0; $i<count($subArray); $i++) {
                    if (count($res) <= $i+1) {
                        $res[]=[];
                    }
                    $res[$i+1]=array_merge($res[$i+1], $subArray[$i]);
                }
            }            
        }
        return $res;
    }
}
