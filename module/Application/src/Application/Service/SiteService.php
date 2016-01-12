<?php

namespace Application\Service;

use Application\Mapper\SimpleMapperInterface;

class SiteService implements SiteServiceInterface
{
    /**
     *
     * @var SimpleMapperInterface
     */
    protected $mapper;
    
    /**
     * @param SiteMapperInterface $mapper
     */
    public function __construct(SimpleMapperInterface $mapper) 
    {
        $this->mapper = $mapper;
    }
    
    /**
     * 
     * {@inheritDoc}
     */
    public function find($site) 
    {
        return $this->mapper->find($site);
    }

    /**
     * 
     * {@inheritDoc}
     */    
    public function findAll() 
    {
        return $this->mapper->findAll();
    }

    /**
     * 
     * {@inheritDoc}
     */
    public function countAll()
    {
        return $this->mapper->countAll();
    }    
}

