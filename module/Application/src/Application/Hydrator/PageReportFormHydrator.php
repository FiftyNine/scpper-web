<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Hydrator;

use Zend\Stdlib\Hydrator\ClassMethods;
use Zend\Stdlib\Hydrator\Filter\MethodMatchFilter;
use Application\Hydrator\Strategy\IntStrategy;
use Application\Form\PageReport\PageReportFieldset;

/**
 * Description of PageReportFormHydrator
 *
 * @author Alexander
 */
class PageReportFormHydrator extends ClassMethods
{
    public function __construct($underscoreSeparatedKeys = true)
    {
        parent::__construct($underscoreSeparatedKeys);
        $this->addStrategy(PageReportFieldset::REPORT_ID, new IntStrategy(true));
        $this->addStrategy(PageReportFieldset::PAGE_ID, new IntStrategy(false));        
        $this->addStrategy(PageReportFieldset::KIND, new IntStrategy(false));
        $this->addStrategy(PageReportFieldset::STATUS, new IntStrategy(false));
        $this->addStrategy(PageReportFieldset::ORIGINAL_ID, new IntStrategy(true));        
        $this->addStrategy('processed', new \Zend\Stdlib\Hydrator\Strategy\BooleanStrategy('1', ''));
        $namingStrat = new \Zend\Stdlib\Hydrator\NamingStrategy\MapNamingStrategy([
            PageReportFieldset::REPORT_ID => 'id',
            PageReportFieldset::PAGE_ID => 'pageId',
            PageReportFieldset::ORIGINAL_ID => 'originalId',
            PageReportFieldset::PAGE_NAME => 'pageName',
            PageReportFieldset::SITE_NAME => 'siteName',
            PageReportFieldset::ORIGINAL_PAGE => 'originalPageName',
            PageReportFieldset::ORIGINAL_SITE => 'originalSiteId',
            PageReportFieldset::HAS_ORIGINAL => 'hasOriginal']);
        $this->setNamingStrategy($namingStrat);        
        $this->addFilter('contributors', new MethodMatchFilter('getContributorsJson'));
        $this->addFilter('page', new MethodMatchFilter('getPage'));
        $this->addFilter('originalPage', new MethodMatchFilter('getOriginalPage'));
    }    
}
