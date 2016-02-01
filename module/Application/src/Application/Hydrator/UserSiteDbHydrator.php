<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Hydrator;

use Zend\Stdlib\Hydrator\HydratorInterface;
use Application\Model\UserInterface;
use Application\Model\MembershipInterface;
use Application\Model\UserActivityInterface;

/**
 * Description of UserSiteHydrator
 *
 * @author Alexander
 */
class UserSiteDbHydrator extends UserDbHydrator
{
    protected $membershipPrototype;
    
    protected $activityPrototype;
    
    protected $membershipHydrator;
    
    protected $activityHydrator;
    
    public function __construct(
            HydratorInterface $membershipHydrator,
            MembershipInterface $membershipPrototype, 
            HydratorInterface $activityHydrator,
            UserActivityInterface $activityPrototype,
            $prefix = ''            
    ) 
    {
        parent::__construct($prefix);
        $this->membershipHydrator = $membershipHydrator;
        $this->membershipPrototype = $membershipPrototype;
        $this->activityHydrator = $activityHydrator;
        $this->activityPrototype = $activityPrototype;     
    }
       
    public function hydrate(array $data, $object) 
    {
        parent::hydrate($data, $object);
        
        if ($object instanceof UserInterface) {
            $activity = $this->activityHydrator->hydrate($data, clone $this->activityPrototype);
            if ($activity->getUserId()) {
                $object->setActivity($activity);
            }
            $membership = $this->membershipHydrator->hydrate($data, clone $this->membershipPrototype);
            if ($membership->getUserId()) {
                $object->setMembership($membership);
            }            
        }        
        return $object;
    }
}
