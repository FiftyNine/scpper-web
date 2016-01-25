<?php

namespace Application\Utils;

class PageType
{
    const ANY = 0;
    const ORIGINAL = 1;
    const TRANSLATION = 2;
    const REWRITE = 3;
    
    const DESCRIPTIONS = array(
        self::ANY => 'Unknown (any)',
        self::ORIGINAL => 'Original',
        self::TRANSLATION => 'Translation',
        self::REWRITE => 'Rewrite'
    );
}