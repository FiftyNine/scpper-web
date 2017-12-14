<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Form\PageReport;

use Zend\Form\Form;
use Zend\Form\Element;

/**
 * Description of ReviewPageReportForm
 *
 * @author Alexander
 */
class ReviewPageReportForm extends Form
{
    
    const REPORT_FIELDSET = 'report';
    
    /**
     * 
     * @param \Application\Service\SiteServiceInterface $siteService
     */
    public function __construct($siteService)
    {
        parent::__construct('review_page_report');
        $this->setAttribute('method', 'post');
        $this->setAttribute('class', 'form form-horizontal page-report-form');
        $fieldset = new PageReportFieldset($siteService);
        $fieldset->setName(self::REPORT_FIELDSET);
        $fieldset->setUseAsBaseFieldset(true);
        $this->add([
            'type' => Element\Hidden::class,
            'name' => 'action',
            'attributes' => [
                'value' => '0'
            ]
        ]);
        $this->add($fieldset);
        $this->add([
            'type' => 'button',
            'name' => 'accept',
            'options' => [
                'label' => 'Accept'
            ],
            'attributes' => [
                'value' => '1',
                'class' => 'btn btn-default report-accept',
            ],
        ]);
        $this->add([
            'type' => 'button',
            'name' => 'ignore',
            'options' => [
                'label' => 'Dismiss'
            ],            
            'attributes' => [
                'value' => '0',
                'class' => 'btn btn-default',                
            ],
        ]);                    
    }
}
