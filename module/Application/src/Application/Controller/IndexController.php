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
use Zend\View\Model\JsonModel;
use Application\Service\HubServiceInterface;
use Application\Utils\PageStatus;
use Application\Utils\AuthorRole;

class IndexController extends AbstractActionController
{
    /**
     *
     * @var HubServiceInterface 
     */
    protected $services;
            
    private function fillExtensionAuthorsInfo($authors, &$info)
    {
        foreach ($authors as $author) {
            $info['authors'][] = [
                'userId' => $author->getUser()->getId(),
                'userName' => $author->getUser()->getName(),
                'user' => $author->getUser()->getDisplayName(),
                'roleId' => $author->getRole(),
                'role' => AuthorRole::getDescription($author->getRole()),
                'deleted' => $author->getUser()->getDeleted()
            ];
        }        
    }
    
    private function fillExtensionPageInfo($pageId, &$info)
    {
        $page = $this->services->getPageService()->find($pageId);
        if (!$page) {
            return false;
        }
        $info['statusId'] = $page->getStatus();
        $info['status'] = PageStatus::getDescription($page->getStatus());
        $info['date'] = $page->getCreationDate()->getTimestamp();        
        $info['authors'] = [];
        $this->fillExtensionAuthorsInfo($page->getAuthors(), $info);
        if ($page->getStatus() === PageStatus::TRANSLATION) {
            $original = $page->getOriginal();
            if ($original) {
                $info['original'] = sprintf('%s/%s', $original->getSite()->getUrl(), $original->getName());            
                $this->fillExtensionAuthorsInfo($original->getAuthors(), $info);
            }
        }
        return true;
    }
    
    public function __construct(HubServiceInterface $hubService) 
    {
        $this->services = $hubService;
    }
    
    public function indexAction()
    {        
        $siteId = $this->services->getUtilityService()->getSiteId();
        $site = $this->services->getSiteService()->find($siteId);
        $result = [
            'site' => $site
        ];
        return new ViewModel($result);
    }
            
    public function selectSiteAction()
    {
        $request = $this->getRequest();
        $response = $this->getResponse();
        $this->services->getUtilityService()->selectSite($request, $response);
        $referer = $request->getHeaders('referer');
        if ($referer) {
            return $this->redirect()->toUrl($referer->getUri());
        }
        return $this->redirect()->toRoute('home');
    }
    
    public function generateConstsAction()
    {
        $this->services->getUtilityService()->generateDbConstants();
        return $this->redirect()->toRoute('home');
    }
    
    public function aboutAction()
    {
        return new ViewModel();
    }
       
    public function extensionPageInfoAction()
    {
        // log request to db
        $result = [
            'status' => 'not_ok',
            'message' => '',
            'data' => []
        ];
        $pageId = $this->params()->fromQuery('pageId', 0);
        if ((int)$pageId > 0 && $this->fillExtensionPageInfo($pageId, $result['data'])) {            
            $result['status'] = 'ok';
        }
        $this->getResponse()->getHeaders()->addHeaders([
            'Access-Control-Allow-Origin' => '*'
        ]);
        return new JsonModel($result);
    }
}
