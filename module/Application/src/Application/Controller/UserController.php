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
    
    public function ratingChartAction()
    {
        $result = array('success' => false);
        $userId = (int)$this->params()->fromQuery('userId');
        $siteId = (int)$this->params()->fromQuery('siteId');
        $user = $this->services->getUserService()->find($userId);
        if ($user) {
            $byDate = new DateAggregate(DbViewVotes::DATETIME, 'Date');
            $count = new Aggregate(DbViewVotes::VALUE, Aggregate::SUM, 'Votes');
            $votes = $this->services->getVoteService()->getAggregatedForUser($userId, $siteId, array($byDate, $count));
            $resVotes = array();
            foreach ($votes as $vote) {
                $resVotes[] = array($vote['Date']->format(\DateTime::ISO8601), (int)$vote['Votes']);
            }        
            $authorships = $this->services->getUserService()->findAuthorshipsOfUser($userId, $siteId);
            $milestones = array();
            foreach ($authorships as $auth) {
                $name = $auth->getPage()->getTitle();
                if (strlen($name) > 11) {
                    $name = substr($name, 0, 8).'...';
                }
                $milestones[] = array(
                    $auth->getPage()->getCreationDate()->format(\DateTime::ISO8601), 
                    array(
                        'name' => $name,
                        'text' => $auth->getPage()->getTitle().' on '.$auth->getPage()->getCreationDate()->format('Y-m-d')
                    )
                );
            }
            $result = array(
                'success' => true,
                'votes' => $resVotes,
                'milestones' => $milestones,
            );
        }
        return new JsonModel($result);
    }    
    
}
