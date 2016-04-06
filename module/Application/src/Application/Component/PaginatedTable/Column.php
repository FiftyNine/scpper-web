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
    const PAGE = 1;
    const DATE = 2;
    const DATE_TIME = 3;
    const INDEX = 4;
    const USERS = 5;
    const OTHER = 6;
    
    /**
     *
     * @var string
     */
    protected $name;
    
    /**
     *
     * @var string
     */
    protected $tooltip;
    
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
     * @var int
     */
    protected $kind;
    
    /**
     * 
     * @param string $name
     * @param string $orderName
     * @param bool $defaultAsc
     */    
    public function __construct($name, $orderName = '', $defaultAsc = true, $tooltip = '', $kind = self::OTHER)
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
        $this->tooltip = $tooltip;
        $this->kind = $kind;
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
     * @return bool
     */
    public function isDefaultAscending()
    {
        return $this->defaultAscending;
    }
    
    /**
     * Tooltip
     * @return string
     */
    public function getTooltip()
    {
        return $this->tooltip;
    }
    
    /**
     * Kind
     * @return int
     */
    public function getKind()
    {
        return $this->kind;
    }    
}
