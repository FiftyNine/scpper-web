<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Form\PageReport;

use Zend\Form\Form;

/**
 * Description of PageReportForm
 *
 * @author Alexander
 */
class NewPageReportForm extends Form
{        
    
    const REPORT_FIELDSET = 'report';
    
    /**
     * 
     * @param \Application\Service\SiteServiceInterface $siteService
     */
    public function __construct($siteService)
    {
        parent::__construct('page_report_submit');
        $this->setAttribute('method', 'post');
        $this->setAttribute('class', $this->getAttribute('class').' form form-horizontal page-report-form');
        $fieldset = new PageReportFieldset($siteService);
        $fieldset->setName(self::REPORT_FIELDSET);
        $fieldset->setUseAsBaseFieldset(true);
        $this->add($fieldset);
        $this->add([
            'name' => 'submit',
            'attributes' => [
                'type' => 'submit',
                'value' => 'Submit',
            ],
        ]);
    }
    
    /**
     * {@inheritDoc}
     */
    public function isValid()
    {
        if (!$this->hasValidated()) {
            $filter = $this->getInputFilter()->get(self::REPORT_FIELDSET);
            if ('2' === $this->data[self::REPORT_FIELDSET][PageReportFieldset::STATUS]) {
                $filter->get(PageReportFieldset::HAS_ORIGINAL)
                        ->setAllowEmpty(false)
                        ->setRequired(true);
                if ($this->data[self::REPORT_FIELDSET][PageReportFieldset::HAS_ORIGINAL]) {
                    $filter->get(PageReportFieldset::ORIGINAL_ID)
                            ->setAllowEmpty(false)
                            ->setRequired(true);
                }
            }
        }
        return parent::isValid();
    }
}
