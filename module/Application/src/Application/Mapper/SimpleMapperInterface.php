<?php

namespace Application\Mapper;

interface SimpleMapperInterface
{
    /**
     * Returns a single object by its id
     * 
     * @param int|string $id WikidotId 
     * @return mixed
     * @throws \InvalidArgumentException
     */
    public function find($id);
    
    /**
     * Returns all available objects
     * 
     * @param array[string] $conditions
     * @param array(string => int) $order Associative array of field names and sorting orders (constants from \Application\Utils\Order)
     * @param bool $paginated 
     * @return ResultSet|\Zend\Paginator\Paginator
     */
    public function findAll($conditions = null, $order = null, $paginated = false);
    
    /**
     * Returns number of all available objects
     * 
     * @return int
     */
    public function countAll();           
}