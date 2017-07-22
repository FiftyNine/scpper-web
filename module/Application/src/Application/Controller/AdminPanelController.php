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
use Application\Model\PageReport;
use Application\Utils\DbConsts\DbViewPageReports;

/**
 * Description of AdminPanelController
 *
 * @author Alexander
 */
class AdminPanelController extends AbstractActionController
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

    public function adminAction()
    {
        $auth = $this->getServiceLocator()->get('Zend\Authentification\AuthentificationService');
        if ($auth->hasIdentity()) {
            return $this->redirect()->toUrl('/admin/reports');
        } else {            
            return $this->redirect()->toUrl('/login?redirect='.urlencode('/admin'));
        }
    }
    
    public function reportsAction()
    {
        $auth = $this->getServiceLocator()->get('Zend\Authentification\AuthentificationService');
        if ($auth->hasIdentity()) {
            $mapper = $this->getServiceLocator()->get('PageReportMapper');
            $form = new \Application\Form\PageReport\ReviewPageReportForm($this->services->getSiteService());
            $request = $this->getRequest();
            if ($request->isPost()) {                
                $report = new PageReport($this->getServiceLocator()->get('PageMapper'));
                $form->bind($report);
                $form->setData($request->getPost());
                if ($form->isValid()) {
                    $report->setProcessed(true);
                    if ('1' === $form->get('action')->getValue()) {
                        $mapper->apply($report);
                    } else {
                        // Ignore
                    }
                    $mapper->save($report);
                }                
            }            
            $reports = $mapper->findAll(
                    [DbViewPageReports::PROCESSED.' <> 1'], 
                    null,
                    true);
            $reports->setCurrentPageNumber(1);
            $reports->setItemCountPerPage(1);            
            if (0 === $reports->getTotalItemCount()) {
                $form = null;
            } else {
                $form->bind($reports->getItem(1));
            }
            return new ViewModel(['reportForm' => $form]);
        } else {            
            return $this->redirect()->toUrl('/login?redirect='.urlencode('/admin'));
        }        
    }
}
