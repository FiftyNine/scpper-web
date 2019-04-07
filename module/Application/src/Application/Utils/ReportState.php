<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Utils;

/**
 * Description of ReportState
 *
 * @author Alexander
 */
class ReportState
{
    const PENDING = 0;
    const ACCEPTED = 1;
    const DISMISSED = 2;
    
    const STATE_DESCRIPTIONS = array(
        self::PENDING => 'Pending',
        self::ACCEPTED => 'Accepted',
        self::DISMISSED => 'Dismissed',
    );
    
    /**
     * Get description for a state
     * @param int $state
     * @throws \InvalidArgumentException
     */
    static public function getDescription($state)
    {        
        if (array_key_exists($state, self::STATE_DESCRIPTIONS)) {
            return self::STATE_DESCRIPTIONS[$state];
        } else {
            throw new \InvalidArgumentException("Unknown report state - $state");
        }        
    }
}