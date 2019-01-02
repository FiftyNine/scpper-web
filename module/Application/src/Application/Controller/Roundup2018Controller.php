<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select;
use Zend\Db\Adapter\Adapter;
use Application\Service\HubServiceInterface;


/**
 * Description of Roundup2018Controller
 *
 * @author Alexander
 */
class Roundup2018Controller extends AbstractActionController
{
    /**
     *
     * @var HubServiceInterface 
     */
    protected $services;    

    public function __construct(HubServiceInterface $hubService)
    {
        $this->services = $hubService;
    }    
    
    public function roundup2018Action()
    {   
//        $siteId = 66711; // $this->services->getUtilityService()->getSiteId();
//        $site = $this->services->getSiteService()->find($siteId);        
//        $reader = new \Zend\Config\Reader\Json();
//        $data = $reader->fromFile('./public/data/2018.json');       
        return new ViewModel([]);
    }
}