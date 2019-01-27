<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
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
    
    public function usersAction()
    {
        $reader = new \Zend\Config\Reader\Json();
        $data = $reader->fromFile(getcwd().'/public/data/roundup/2018/users.json');
        return new ViewModel($data);
    }
        
    public function pagesAction()
    {                   
        $reader = new \Zend\Config\Reader\Json();
        $data = $reader->fromFile(getcwd().'/public/data/roundup/2018/pages.json');
        
        return new ViewModel($data);
    }
}