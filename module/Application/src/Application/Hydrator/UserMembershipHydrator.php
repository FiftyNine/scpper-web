<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Hydrator;

use Zend\Stdlib\Hydrator\HydratorInterface;
use Application\Model\User;
use Application\Utils\DbConsts\DbViewMembership;

/**
 * Description of UserDbHydrator
 *
 * @author Alexander
 */
class UserMembershipHydrator implements HydratorInterface
{
    public function extract($object) 
    {
        return array();
    }

    public function hydrate(array $data, $object) 
    {
        if ($object instanceof User 
                && array_key_exists(DbViewMembership::SITEID, $data) 
                && array_key_exists(DbViewMembership::JOINDATE, $data) 
                && $data[DbViewMembership::JOINDATE]
        ) {
            $object->addMembership($data[DbViewMembership::SITEID], $data[DbViewMembership::JOINDATE]);
        }
        return $object;
    }
}
