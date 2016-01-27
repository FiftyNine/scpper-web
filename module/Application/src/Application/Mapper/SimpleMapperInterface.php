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
     * @param bool $paginated
     * @return ResultSet|\Zend\Paginator\Paginator
     */
    public function findAll($paginated = true);
    
    /**
     * Returns number of all available objects
     * 
     * @return int
     */
    public function countAll();           
}