<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Hydrator;

use Zend\Stdlib\Hydrator\NamingStrategy\MapNamingStrategy;
use Application\Utils\DbConsts\DbViewUsers;
use Application\Utils\DbSelectColumns;

/**
 * Description of UserDbHydrator
 *
 * @author Alexander
 */
class UserDbHydrator extends PrefixDbHydrator
{
    public function __construct($prefix = '')
    {        
        parent::__construct();
        $map = [
            DbViewUsers::USERID => 'id',
            DbViewUsers::WIKIDOTNAME => 'name'
        ];
        if ($prefix && is_string($prefix)) {
            $map = $this->getPrefixedMap($prefix, DbSelectColumns::USER, $map);
        }
        $this->setNamingStrategy(new MapNamingStrategy($map));
    }
}
