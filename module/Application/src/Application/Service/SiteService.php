<?php

namespace Application\Service;

use Application\Mapper\SiteMapperInterface;

class SiteService implements SiteServiceInterface
{
    /**
     *
     * @var SiteMapperInterface
     */
    protected $mapper;
    
    /**
     * @param SiteMapperInterface $mapper
     */
    public function __construct(SiteMapperInterface $mapper) 
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
    public function findByShortName($site)
    {
        $sites = $this->mapper->findAll([\Application\Utils\DbConsts\DbViewSites::SHORTNAME => $site]);
        if ($sites->count() != 1) {
            throw new \InvalidArgumentException();
        }
        return $sites->current();
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

