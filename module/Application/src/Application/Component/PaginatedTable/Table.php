<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Component\PaginatedTable;

/**
 * Description of AbstractTable
 *
 * @author Alexander
 */
class Table implements TableInterface
{
    /**
     *
     * @var \Application\Component\PaginatedTable\TableColumns;
     */
    protected $columns;
    
    /**
     * @var \Zend\Paginator\Paginator;
     */
    protected $paginator;
    
    /**
     *
     * @var string
     */
    protected $bodyView;    
    
    /**
     *
     * @var bool
     */
    protected $preview;
    
    public function __construct($columns, $paginator, $bodyView, $preview = false) 
    {
        $this->columns = new TableColumns($columns);
        $this->paginator = $paginator;
        $this->bodyView = $bodyView;
        $this->preview = $preview;
    }
    
    /**
     * {@inheritDoc}
     */
    public function getColumns() 
    {
        return $this->columns;
    }
    
    /**
     * {@inheritDoc}
     */
    public function getPaginator() 
    {
        return $this->paginator;
    }

    /**
     * {@inheritDoc}
     */        
    public function getBodyView() 
    {
        return $this->bodyView;
    }    
    
    /**
     * {@inheritDoc}
     */        
    public function isPreview() 
    {
        return $this->preview;        
    }    
}
