<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Component\PaginatedTable;

/**
 * Description of Column
 *
 * @author Alexander
 */
class Column 
{
    /**
     *
     * @var string
     */
    protected $name;
    
    /**
     *
     * @var string
     */
    protected $orderName;
    
    /**
     *
     * @var bool
     */
    protected $defaultAscending;
    
    /**
     * 
     * @param string $name
     * @param string $orderName
     * @param bool $defaultAsc
     */    
    public function __construct($name, $orderName = '', $defaultAsc = true)
    {
        if (is_string($name)) {
            $this->name = $name;
        } else {
            $this->name = 'Column';
        }
        if (is_string($orderName) && $orderName) {
            $this->orderName = $orderName;
        }
        $this->defaultAscending = $defaultAsc;
    }
    
    /**
     * 
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
    
    /**
     * 
     * @return string
     */
    public function getOrderName()
    {
        return $this->orderName;
    }
    
    /**
     * Shows whether table can be order by this column
     * @return bool
     */
    public function canOrder()
    {
        return isset($this->orderName);
    }
    
    /**
     * Show if default order for this column is ascending
     */
    public function isDefaultAscending()
    {
        return $this->defaultAscending;
    }
}
