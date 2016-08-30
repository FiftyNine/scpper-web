<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Factory\Prototype;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Application\Model\Page;

/**
 * Description of SitePrototypeFactory
 *
 * @author Alexander
 */
class PagePrototypeFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator) 
    {
        $siteMapper = $serviceLocator->get('SiteMapper');        
        $pageMapper = $serviceLocator->get('PageMapper');
        $authorMapper = $serviceLocator->get('AuthorshipMapper');
        $revisionMapper = $serviceLocator->get('RevisionMapper');
        $voteMapper = $serviceLocator->get('VoteMapper');
        $tagMapper = $serviceLocator->get('TagMapper');
        return new Page($siteMapper, $pageMapper, $authorMapper, $revisionMapper, $voteMapper, $tagMapper);
    }
}
