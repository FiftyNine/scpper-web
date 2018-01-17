<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Hydrator;

use Zend\Stdlib\Hydrator\Strategy\DateTimeFormatterStrategy;
use Zend\Stdlib\Hydrator\NamingStrategy\MapNamingStrategy;
use Application\Utils\DbConsts\DbViewUserActivity;
use Application\Utils\DbSelectColumns;

/**
 * Description of UserActivityDbHydrator
 *
 * @author Alexander
 */
class UserActivityDbHydrator extends PrefixDbHydrator
{
    public function __construct($prefix = '')
    {        
        parent::__construct();
        $map = [
            DbViewUserActivity::VOTES => 'voteCount',
            DbViewUserActivity::REVISIONS => 'revisionCount',
            DbViewUserActivity::PAGES => 'authorshipCount',
        ];
        $lastActivityName = DbViewUserActivity::LASTACTIVITY;
        if ($prefix && is_string($prefix)) {
            $map = $this->getPrefixedMap($prefix, DbSelectColumns::USER_ACTIVITY, $map);
            $lastActivityName = $prefix.'_'.$lastActivityName;
        }
        $this->setNamingStrategy(new MapNamingStrategy($map));
        $this->addStrategy($lastActivityName, new DateTimeFormatterStrategy('Y-m-d H:i:s'));
    }
}
