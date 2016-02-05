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
use Application\Utils\DbConsts\DbViewPages;
use Application\Utils\PageType;
use Application\Utils\Order;

/**
 * Description of PageController
 *
 * @author Alexander
 */
class PageController extends AbstractActionController 
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

    public function pageAction()
    {
        $pageId = (int)$this->params()->fromRoute('pageId');
        $page = $this->services->getPageService()->find($pageId);
        if (!$page) {
            return $this->notFoundAction();
        }
        return new ViewModel(array(
           'page' => $page,
        ));
    }
}
