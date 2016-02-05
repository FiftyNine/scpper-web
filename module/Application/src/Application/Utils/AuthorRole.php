<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Utils;

/**
 * Description of AuthorRole
 *
 * @author Alexander
 */
class AuthorRole 
{
    const AUTHOR = 1;
    const REWRITE_AUTHOR = 2;
    const TRANSLATOR = 3;
    const CONTRIBUTOR = 4;
    
    const ROLE_DESCRIPTIONS = array(
        self::AUTHOR => 'Author',
        self::REWRITE_AUTHOR => 'Rewrite author',
        self::TRANSLATOR => 'Translator',
        self::CONTRIBUTOR => 'Contributor',
    );
    
    /**
     * Get description for a role
     * @param int $role
     * @throws \InvalidArgumentException
     */
    static public function getDescription($role)
    {        
        if (array_key_exists($role, self::ROLE_DESCRIPTIONS)) {
            return self::ROLE_DESCRIPTIONS[$role];
        } else {
            throw new \InvalidArgumentException("Unknown author role - $role");
        }        
    }
}
