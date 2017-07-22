<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Service;

use Zend\EventManager\EventManagerAwareInterface;
use Zend\EventManager\EventInterface;
use Zend\Log\LoggerInterface;
use Application\Utils\PostInitializationInterface;

/**
 * Description of EventLogger
 *
 * @author Alexander
 */
class EventLogger implements EventManagerAwareInterface, PostInitializationInterface
{
    use \Zend\EventManager\EventManagerAwareTrait;
    
    /**
     *
     * @var LoggerInterface
     */
    protected $logger;
    
    public function __construct(LoggerInterface $logger) 
    {
        $this->logger = $logger;        
    }
    
    /**
     * Log information about a database query
     * @param \Application\Service\EventInterface $e
     */
    public function logQuery(EventInterface $e)
    {
        $text = $e->getParam('text');
        $this->logger->debug($text);
    }
    
    /**
     * {@inheritDoc}
     */
    public function postInitialize()
    {        
        if (defined('\SCPPER_DEBUG')) {
            $this->getEventManager()->getSharedManager()->attach('*', \Application\Utils\Events::LOG_SQL_QUERY, array($this, 'logQuery'));
        }
    }
}