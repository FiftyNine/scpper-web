<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Service\HubServiceInterface;
use Application\Utils\PageType;
use Application\Utils\UserType;
use Application\Utils\VoteType;
use Application\Form\SearchForm;

class IndexController extends AbstractActionController
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
    
    public function indexAction()
    {                
        $siteId = $this->services->getUtilityService()->getSiteId();
        $site = $this->services->getSiteService()->find($siteId);
        $result = array(
            'site' => $site,
            'members' => $this->services->getUserService()->countSiteMembers($siteId),
            'contributors' => $this->services->getUserService()->countSiteMembers($siteId, UserType::CONTRIBUTOR),
            'posters' => $this->services->getUserService()->countSiteMembers($siteId, UserType::getTypeMask(false, true, true)),
            'active' => $this->services->getUserService()->countSiteMembers($siteId, UserType::ANY, true),
            'pages' => $this->services->getPageService()->countSitePages($siteId),
            'originals' => $this->services->getPageService()->countSitePages($siteId, PageType::ORIGINAL),
            'translations' => $this->services->getPageService()->countSitePages($siteId, PageType::TRANSLATION),
            'rewrites' => $this->services->getPageService()->countSitePages($siteId, PageType::REWRITE),
            'revisions' => $this->services->getRevisionService()->countSiteRevisions($siteId),
            'votes' => $this->services->getVoteService()->countSiteVotes($siteId),
            'positive' => $this->services->getVoteService()->countSiteVotes($siteId, VoteType::POSITIVE),
        );
        $result['negative'] = $result['votes']-$result['positive'];
        return new ViewModel($result);
    }
    
    public function searchAction()
    {        
        $request = $this->getRequest();
        $form = $this->services->getUtilityService()->getSearchForm();
        $result = array('form' => $form);
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $text = $form->get(SearchForm::TEXT_FIELD_NAME)->getValue();
                $text = trim($text);
                if (mb_strlen($text) >= 3) {
                    $result['pages'] = $this->services->getPageService()->findByName($text);
                    $result['users'] = $this->services->getUserService()->findByName($text);
                }
            }
        }        
        return new ViewModel($result);
    }
        
    public function selectSiteAction()
    {
        $request = $this->getRequest();
        $response = $this->getResponse();
        $this->services->getUtilityService()->selectSite($request, $response);
        return $this->redirect()->toRoute('home');
    }
    
    public function generateConstsAction()
    {
        $this->services->getUtilityService()->generateDbConstants();
        return $this->redirect()->toRoute('home');
    }
}
