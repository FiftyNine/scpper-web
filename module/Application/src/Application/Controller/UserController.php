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
use Application\Service\HubServiceInterface;
use Application\Factory\Component\PaginatedTableFactory;
use Application\Utils\Aggregate;
use Application\Utils\DateAggregate;
use Application\Utils\Order;
use Application\Utils\DbConsts\DbViewVotes;
use Application\Utils\DbConsts\DbViewRevisions;


/**
 * Description of UserController
 *
 * @author Alexander
 */
class UserController extends AbstractActionController 
{
    /**
     *
     * @var Application\Service\ServiceHubInterface
     */    
    protected $services;
    
    public function __construct(HubServiceInterface $services) 
    {
        $this->services = $services;
    }

    public function userAction()
    {
        $siteId = $this->services->getUtilityService()->getSiteId();
        $site = $this->services->getSiteService()->find($siteId);
        $userId = (int)$this->params()->fromRoute('userId');
        $user = $this->services->getUserService()->find($userId);
        if (!$user) {
            return $this->notFoundAction();
        }
        return new ViewModel(array(
            'user' => $user,
            'site' => $site,
        ));
    }
    
}
