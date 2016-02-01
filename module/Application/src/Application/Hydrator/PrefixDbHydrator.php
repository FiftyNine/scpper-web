<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Hydrator;

use Zend\Stdlib\Hydrator\ClassMethods;

/**
 * Description of PrefixDbHydrator
 *
 * @author Alexander
 */
class PrefixDbHydrator extends ClassMethods
{
    /**
     * Get a map for MapNamingStrategy where each field is prefixed with a string     
     * @param string $prefix
     * @param array[string] $columns
     * @param array[string]string $oldMap
     * @return array[string]string
     */
    protected function getPrefixedMap($prefix, $columns, $oldMap = array())
    {
        $newmap = array();
        foreach ($oldMap as $key => $value) {
            $newmap[$prefix.'_'.$key] = $value;
        }
        foreach ($columns as $col) {
            $name = $prefix.'_'.$col;
            if (!array_key_exists($name, $newmap)) {
                $newmap[$name] = $col;
            }
        }
        return $newmap;
    }
}
