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
use Application\Model\PageReport;
use Application\Utils\Order;
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
    
    /**
     * 
     * @param int $page
     * @param int $perPage
     * @return Application\Component\TableInterface
     */
    protected function getReportsTable($page, $perPage)
    {
        $reports = $this->getServiceLocator()->get('PageReportMapper')->findAll([], [DbViewPageReports::ID => Order::DESCENDING], true);
        $reports->setCurrentPageNumber($page);
        $reports->setItemCountPerPage($perPage);
        $table = \Application\Factory\Component\PaginatedTableFactory::createReportsTable($reports);
        return $table;
    }
    
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
    
    public function reportAction()
    {
        $auth = $this->getServiceLocator()->get('Zend\Authentification\AuthentificationService');
        if (!$auth->hasIdentity()) {
            return $this->redirect()->toUrl('/login?redirect='.urlencode('/admin'));
        }        
        $mapper = $this->getServiceLocator()->get('PageReportMapper');
        $form = new \Application\Form\PageReport\ReviewPageReportForm($this->services->getSiteService());
        $request = $this->getRequest();
        if ($request->isPost()) {                
            $report = new PageReport($this->getServiceLocator()->get('PageMapper'));
            $form->bind($report);
            $form->setData($request->getPost());
            if ($form->isValid()) {                
                if ('1' === $form->get('action')->getValue()) {
                    $report->setReportState(\Application\Utils\ReportState::ACCEPTED);
                    $mapper->apply($report);                    
                } else {
                    $report->setReportState(\Application\Utils\ReportState::DISMISSED);
                    // Ignore                    
                }
                $mapper->save($report);
            }
            // return $this->redirect()->toUrl('/admin/report');
        }
        $reportId = (int)$this->params()->fromRoute('reportId');
        $report = null;
        if (0 === $reportId) {
            $reports = $mapper->findAll(
                    [DbViewPageReports::REPORTSTATE.' = '.\Application\Utils\ReportState::PENDING], 
                    null,
                    true);
            $reports->setCurrentPageNumber(1);
            $reports->setItemCountPerPage(1);
            if ($reports->getTotalItemCount() > 0) {
                $report = $reports->getItem(1);
            }
        } else {
            try {
                $report = $mapper->find($reportId);
            } catch (Exception $e) {                    
            }
        }        
        if ($report == null) {
            $form = null;
        } else {
            $form->bind($report);
        }
        return new ViewModel(['reportForm' => $form]);        
    }    
    
    public function reportsAction()
    {
        $auth = $this->getServiceLocator()->get('Zend\Authentification\AuthentificationService');
        if (!$auth->hasIdentity()) {
            return $this->redirect()->toUrl('/login?redirect='.urlencode('/admin'));
        }
        $table = $this->getReportsTable(1, 10);
        return new ViewModel(['table' => $table]);
    }
    
    public function reportListAction()
    {
        $result = ['success' => false];
        $auth = $this->getServiceLocator()->get('Zend\Authentification\AuthentificationService');
        if ($auth->hasIdentity()) {                        
            $page = (int)$this->params()->fromQuery('page', 1);
            $perPage = (int)$this->params()->fromQuery('perPage', 10);
            $table = $this->getReportsTable($page, $perPage);
            $renderer = $this->getServiceLocator()->get('ViewHelperManager')->get('partial');
            if ($renderer) {
                $result['success'] = true;                
                $result['content'] = $renderer(
                    'partial/tables/default/table.phtml', 
                    [
                        'table' => $table, 
                    ]
                );
            }
        }
        return new JsonModel($result);   
    }       
}
