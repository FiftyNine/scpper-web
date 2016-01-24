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
    protected $description;
    
    public function __construct($name, $description)
    {
        if (is_string($name)) {
            $this->name = $name;
        } else {
            $this->name = '';
        }
        if (is_string($description)) {
            $this->description = $description;
        } else {
            $this->description = 'Column';
        }
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
    public function getDescription()
    {
        return $this->description;
    }
}
