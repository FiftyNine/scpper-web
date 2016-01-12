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
     * @param int $offset Offset from the beginning
     * @param int $limit Maximum number of rows to fetch (<=0 for all rows)
     * @return ResultSet
     */
    public function findAll($offset = 0, $limit = 0);
    
    /**
     * Returns number of all available objects
     * 
     * @return int
     */
    public function countAll();           
}