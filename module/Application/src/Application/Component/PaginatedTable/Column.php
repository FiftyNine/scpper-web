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
     * @var double
     */
    protected $width;
    
    /**
     * @var int
     */
    protected $collapseAt;
    
    /**
     * 
     * @var \Application\Component\PaginatedTable\ColumnList
     */
    protected $subColumns;
            
    /**
     * 
     * @param string $name
     * @param double $width 
     * @param string $orderName
     * @param bool $defaultAsc         
     * @param int $collapseAt
     * @param int $kind 
     * @param mixed $subColumns Associative array of column names => descriptions or array of Column objects     
     */
    protected function __construct($name, $width, $orderName = '', $defaultAsc = true, $tooltip = '', $collapseAt = 0, $kind = self::OTHER, $subColumns = [])
    {
        if (is_string($name)) {
            $this->name = $name;
        } else {
            $this->name = '';
        }
        $this->width = $width;
        if (is_string($orderName) && $orderName) {
            $this->orderName = $orderName;
        }
        $this->defaultAscending = $defaultAsc;
        $this->tooltip = $tooltip;
        $this->kind = $kind;
        $this->collapseAt = $collapseAt;
        $this->subColumns = new ColumnList($subColumns);
    }
    
    /**
     * 
     * @param string $name
     * @param double $width 
     * @param string $orderName
     * @param bool $defaultAsc   
     * @param int $kind 
     */    
    public static function column($name, $width, $orderName = '', $defaultAsc = true, $tooltip = '', $kind = self::OTHER)
    {
        return new self($name, $width, $orderName, $defaultAsc, $tooltip, 0, $kind, []);
    }

    /**
     * @param mixed $subColumns Associative array of column names => descriptions or array of Column objects     
     * @param double $width      
     * @param int $collapseAt     
     * @param string $name
     */    
    public static function group($subColumns, $width, $collapseAt = 0, $name = '')
    {
        return new self($name, $width, '', true, '', $collapseAt, self::OTHER, $subColumns);
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
     * @return double
     */
    public function getWidth()
    {
        return $this->width;
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
        return ($this->subColumns->getCount() == 0) && isset($this->orderName);
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

    /**
     * Screen width value, under which subcolumns will collapse into a vertical list
     * @return int
     */
    public function getCollapseAt()
    {
        return $this->collapseAt;
    }
    
    /**
     * SubColumns of this column
     * @return \Application\Component\PaginatedTable\TableColumns
     */
    public function getSubColumns()
    {
        return $this->subColumns;
    }
    
    /**
     * Returns number of child columns of the lowest level
     * Translates into "colspan" html attribute
     * @return int
     */
    public function getColSpan()
    {
        if ($this->subColumns->getCount() == 0) {
            $result = 1;
        } else {
            $result = 0;
            foreach ($this->subColumns as $index => $sub) {
                if (!$sub->getHidden()) {
                    $result+=$sub->getColSpan();
                }
            }                        
        }        
        return $result;
    }    
}
