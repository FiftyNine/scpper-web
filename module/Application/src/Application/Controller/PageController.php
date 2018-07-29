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
use Application\Form\PageReport\NewPageReportForm;
use Application\Form\PageReport\PageReportFieldset;
use Application\Form\PageReport\PageReportContributorFieldset;
use Application\Model\PageReport;
use Application\Utils\Aggregate;
use Application\Utils\DateAggregate;
use Application\Utils\Order;
use Application\Utils\DbConsts\DbViewVotes;
use Application\Utils\DbConsts\DbViewRevisions;

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

    /**
     * Returns a paginated table with revisions
     * @param int $pageId
     * @param int $orderBy
     * @param string $order
     * @param int $page
     * @param int $perPage
     * @return Application\Component\PaginatedTable\TableInterface
     */
    protected function getRevisionsTable($pageId, $orderBy, $order, $page, $perPage)
    {
        $revisions = $this->services->getRevisionService()->findRevisionsOfPage($pageId, [$orderBy => $order], true, $page, $perPage);
        $table = PaginatedTableFactory::createRevisionsTable($revisions);
        $table->getColumns()->setOrder($orderBy, $order === Order::ASCENDING);        
        return $table;        
    }

    /**
     * Returns a paginated table with votes
     * @param int $pageId
     * @param string $orderBy
     * @param int $order
     * @param int $page
     * @param int $perPage
     * @return Application\Component\PaginatedTable\TableInterface
     */
    protected function getVotesTable($pageId, $orderBy, $order, $page, $perPage)
    {
        $votes = $this->services->getVoteService()->findVotesOnPage($pageId, [$orderBy => $order], true, $page, $perPage);
        $table = PaginatedTableFactory::createPageVotesTable($votes);
        $table->getColumns()->setOrder($orderBy, $order === Order::ASCENDING);
        return $table;
    }
    
    public function __construct(HubServiceInterface $services) 
    {
        $this->services = $services;
    }

    public function pageAction()
    {
        $pageId = (int)$this->params()->fromRoute('pageId');
        try {
            $page = $this->services->getPageService()->find($pageId);
        } catch (\InvalidArgumentException $e) {
            return $this->notFoundAction();
        }
        if (!$page) {
            return $this->notFoundAction();
        }        
        $reportForm = new NewPageReportForm($this->services->getSiteService());
        $fieldset = $reportForm->getBaseFieldset();
        $fieldset->get(PageReportFieldset::PAGE_ID)->setValue($page->getId());
        $fieldset->get(PageReportFieldset::SITE_NAME)->setValue($page->getSite()->getEnglishName());
        $fieldset->get(PageReportFieldset::PAGE_NAME)->setValue($page->getTitle());
        $fieldset->get(PageReportFieldset::STATUS)->setValue($page->getStatus());        
        $fieldset->get(PageReportFieldset::HAS_ORIGINAL)->setChecked($page->getOriginal() !== null);
        if ($page->getOriginal()) {
            $fieldset->get(PageReportFieldset::ORIGINAL_ID)->setValue($page->getOriginal()->getId());
            $fieldset->get(PageReportFieldset::ORIGINAL_SITE)->setValue($page->getOriginal()->getSite()->getId());
            $fieldset->get(PageReportFieldset::ORIGINAL_PAGE)->setValue($page->getOriginal()->getTitle());
        }
        $fieldset->get(PageReportFieldset::KIND)->setValue($page->getKind());
        $contributors = [];
        foreach ($page->getAuthors() as $authorship) {            
            $contributors[] = [
                PageReportContributorFieldset::USER_NAME => $authorship->getUser()->getDisplayName(),
                PageReportContributorFieldset::USER_ID => $authorship->getUser()->getId(),
                PageReportContributorFieldset::ROLE => $authorship->getRole(),
            ];
        }
        $fieldset->get(PageReportFieldset::CONTRIBUTORS)->populateValues($contributors);
        return new ViewModel([
            'page' => $page,
            'revisions' => $this->getRevisionsTable($pageId, DbViewRevisions::REVISIONINDEX, Order::DESCENDING, 1, 10),
            'votes' => $this->getVotesTable($pageId, DbViewVotes::DATETIME, Order::DESCENDING, 1, 10),
            'reportForm' => $reportForm
        ]);
    }
    
    public function apiPageAction()
    {
        $pageId = (int)$this->params()->fromQuery('id');
        try {
            $page = $this->services->getPageService()->find($pageId);
        } catch (\InvalidArgumentException $e) {
            return new JsonModel(['error' => 'Page not found']);
        }
        return new JsonModel($page->toArray());
    }
    
    public function ratingChartAction()
    {
        $pageId = (int)$this->params()->fromQuery('pageId');
        $votes = $this->services->getVoteService()->getChartDataForPage($pageId);
        $resVotes = [];
        foreach ($votes as $vote) {
            $resVotes[] = [$vote['Date']->format(\DateTime::ISO8601), (int)$vote['Votes']];
        }
        $revisions = $this->services->getRevisionService()->findRevisionsOfPage($pageId);
        $resRevisions = [];
        foreach ($revisions as $rev) {
            $resRevisions[] = [
                $rev->getDateTime()->format(\DateTime::ISO8601), 
                [
                    'name' => (string)($rev->getIndex()+1),
                    'text' => $rev->getComments()==='' ? $rev->getUser()->getDisplayName() : sprintf('%s: "%s"', $rev->getUser()->getDisplayName(), $rev->getComments())
                ]
            ];
        }
        return new JsonModel([
            'success' => true,
            'votes' => $resVotes,
            'milestones' => $resRevisions,
        ]);
    }
    
    public function revisionListAction()
    {
        $pageId = (int)$this->params()->fromQuery('pageId');
        $page = (int)$this->params()->fromQuery('page', 1);
        $perPage = (int)$this->params()->fromQuery('perPage', 10);
        $orderBy = $this->params()->fromQuery('orderBy', DbViewRevisions::REVISIONINDEX);
        $order = $this->params()->fromQuery('ascending', true);
        if ($order) {
            $order = Order::ASCENDING;
        } else {
            $order = Order::DESCENDING;
        }    
        $table = $this->getRevisionsTable($pageId, $orderBy, $order, $page, $perPage);
        $renderer = $this->getServiceLocator()->get('ViewHelperManager')->get('partial');
        if ($renderer) {
            $result['success'] = true;                
            $result['content'] = $renderer(
                'partial/tables/default/table.phtml', 
                [
                    'table' => $table, 
                    'data' => []
                ]
            );
        }
        return new JsonModel($result);                
    }
    
    public function voteListAction()
    {
        $pageId = (int)$this->params()->fromQuery('pageId');
        $page = (int)$this->params()->fromQuery('page', 1);
        $perPage = (int)$this->params()->fromQuery('perPage', 10);
        $orderBy = $this->params()->fromQuery('orderBy', DbViewVotes::DATETIME);
        $order = $this->params()->fromQuery('ascending', true);
        if ($order) {
            $order = Order::ASCENDING;
        } else {
            $order = Order::DESCENDING;
        }    
        $table = $this->getVotesTable($pageId, $orderBy, $order, $page, $perPage);
        $renderer = $this->getServiceLocator()->get('ViewHelperManager')->get('partial');
        if ($renderer) {
            $result['success'] = true;                
            $result['content'] = $renderer(
                'partial/tables/default/table.phtml', 
                [
                    'table' => $table, 
                    'data' => []
                ]
            );
        }
        return new JsonModel($result);                
    }
    
    public function reportAction()
    {
        $response = ['success' => false];
        $form = new NewPageReportForm($this->services->getSiteService());
        $report = new PageReport($this->getServiceLocator()->get('PageMapper'));
        $form->bind($report);
        $request = $this->getRequest();

        if ($request->isPost()) {            
            $form->setData($request->getPost());
            if ($form->isValid()) {                
                $response['success'] = true;                
                $mapper = $this->getServiceLocator()->get('PageReportMapper');
                $mapper->save($report);
            } else {
                $response['messages'] = $form->getMessages();
            }            
        } else {
            $response['messages'] = ['Not a POST'];
        }
        return new JsonModel($response);
    }
}
