<?php

namespace Application\Service;

use Application\Model\SiteInterface;

interface SiteServiceInterface
{
    /**
     * Returns a single site
     * 
     * @param int|string $site Site's WikidotId or WikidotName
     * @return SiteInterface
     * @throws \InvalidArgumentException
     */
    public function find($site);

    /**
     * Returns a single site
     * 
     * @param int|string $site Sites short name (en|ru|pl...)
     * @return SiteInterface
     * @throws \InvalidArgumentException
     */
    public function findByShortName($site);
    
    /**
     * Returns all available sites
     * 
     * @return array|SiteInterface[]
     */
    public function findAll();
    
    /**
     * Returns number of available sites
     * 
     * @return int
     */
    public function countAll();
}
