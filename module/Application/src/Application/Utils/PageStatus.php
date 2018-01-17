<?php

namespace Application\Utils;

class PageStatus
{
    const ANY = 0;
    const ORIGINAL = 1;
    const TRANSLATION = 2;
    const REWRITE = 3;
    
    const DESCRIPTIONS = [
        self::ANY => 'Unknown (any)',
        self::ORIGINAL => 'Original',
        self::TRANSLATION => 'Translation',
        self::REWRITE => 'Rewrite'
    ];
    
    /**
     * Get description for page status
     * @param int $status
     * @throws \InvalidArgumentException
     */
    static public function getDescription($status)
    {
        if (array_key_exists($status, self::DESCRIPTIONS)) {
            return self::DESCRIPTIONS[$status];
        } else {
            throw new \InvalidArgumentException("Unknown page status - $status");
        }                
    }
}