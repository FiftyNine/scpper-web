<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Utils;

/**
 * Description of UserType
 *
 * @author Alexander
 */
class UserType 
{           
    const ANY = 0;    
    const VOTER = 1;
    const CONTRIBUTOR = 2;
    const POSTER = 4;
    
    /**
     * Return mask for a query, where each flag shows if a user MUST fill respective role
     * Example: getTypeMask(true, false, true) => b101 => 5 => User must be voter and poster
     * Example: getTypeMask(false, true, false) => b10 => 2 => User must be contributor
     * Example: getTypeMask(false, false, false) => b0 => 0 => Any user
     * @param bool $voter
     * @param bool $contributor
     * @param bool $poster
     * @return int
     */
    public static function getTypeMask($voter, $contributor, $poster)
    {
        $result = self::ANY;
        if ($voter) {
            $result |= self::VOTER;
        }
        if ($contributor) {
            $result |= self::CONTRIBUTOR;
        }
        if ($poster) {
            $result |= self::POSTER;
        }
        return $result;
    }
}
