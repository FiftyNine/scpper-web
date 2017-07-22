<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Hydrator\Strategy;

use Zend\Stdlib\Hydrator\Strategy\StrategyInterface;

/**
 * Description of IntStrategy
 *
 * @author Alexander
 */
class IntStrategy implements StrategyInterface
{
    /**
     * @var bool
     */
    protected $allowEmpty;
    
    public function __construct($allowEmpty)
    {
        $this->allowEmpty = $allowEmpty;
    }
    
    public function extract($value)
    {
        return $value;
    }

    public function hydrate($value)
    {
        if ((is_null($value) || '' === $value) && ($this->allowEmpty)) {
            return null;
        } else if (is_int($value) || (strval(intval($value)) === $value)) {
            return intval($value);
        } else {
            throw new \InvalidArgumentException();
        }
    }
}
