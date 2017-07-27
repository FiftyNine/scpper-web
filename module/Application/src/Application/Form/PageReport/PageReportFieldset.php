<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Form\PageReport;

use Zend\Form\Fieldset;
use Zend\Form\Element;
use Zend\InputFilter\InputFilterProviderInterface;
use Application\Utils\PageStatus;
use Application\Utils\PageKind;


/**
 * Description of PageReportFieldset
 *
 * @author Alexander
 */
class PageReportFieldset extends Fieldset implements InputFilterProviderInterface
{
    const REPORT_ID = 'report-id';
    const PAGE_ID = 'page-id';
    const SITE_NAME = 'site-name';
    const PAGE_NAME = 'page-name';    
    const REPORTER = 'reporter';
    const KIND = 'kind';
    const STATUS = 'status';
    const HAS_ORIGINAL = 'has-original';
    const ORIGINAL_ID = 'original-id';
    const ORIGINAL_SITE = 'original-site';
    const ORIGINAL_PAGE= 'original-page';
    const CONTRIBUTORS = 'contributors';    
    const USER_ADD = 'user-add';
    
    /**
     * 
     * @param \Application\Service\SiteServiceInterface $siteService
     */
    public function __construct($siteService)
    {
        parent::__construct('page_report');
        $hydrator = new \Application\Hydrator\PageReportFormHydrator(false);        
        $this->setHydrator($hydrator);        
        $this->add([
            'name' => self::REPORT_ID,
            'attributes' => [
                'class' => 'form-control',
            ],
            'type' => Element\Hidden::class,
        ]);        
        $this->add([
            'name' => self::PAGE_ID,
            'attributes' => [
                'required' => 'required',
                'class' => 'form-control',
            ],
            'type' => Element\Hidden::class,
        ]);
        $this->add([
            'name' => self::SITE_NAME,
            'options' => [
                'label' => 'Branch',
                'label_attributes' => [
                    'class' => 'control-label'
                ]
            ],
            'attributes' => [
                'class' => 'form-control',
                'readonly' => '',
                'disabled' => 'disabled'
            ],
            'type' => Element\Text::class,
        ]);        
        $this->add([
            'name' => self::PAGE_NAME,
            'options' => [
                'label' => 'Page',
                'label_attributes' => [
                    'class' => 'control-label'
                ]                
            ],
            'attributes' => [
                'class' => 'form-control',
                'readonly' => '',
                'disabled' => 'disabled'
            ],
            'type' => Element\Text::class,
        ]);
        $this->add([
            'name' => self::REPORTER,
            'options' => [
                'label' => 'Reporter',
                'label_attributes' => [
                    'class' => 'control-label'
                ]                
            ],
            'attributes' => [
                'class' => 'form-control',
            ],            
            'type' => Element\Text::class,
        ]);
        $kinds = PageKind::DESCRIPTIONS;
        unset($kinds[PageKind::UNKNOWN]);
        $this->add([
            'name' => self::KIND,
            'options' => [
                'label' => 'Kind*',
                'value_options' => $kinds,
                'empty_option' => 'Unknown'
            ],
            'attributes' => [
                'class' => 'form-control',
            ],            
            'type' => Element\Select::class,
        ]);          
        $this->add([
            'name' => self::STATUS,
            'options' => [
                'label' => 'Status*',
                'label_attributes' => [
                    'class' => 'control-label'
                ],                
                'value_options' => [
                    PageStatus::ORIGINAL => PageStatus::getDescription(PageStatus::ORIGINAL),
                    PageStatus::TRANSLATION => PageStatus::getDescription(PageStatus::TRANSLATION),
                ]
            ],
            'attributes' => [
                'class' => 'form-control',
            ],
            'type' => Element\Select::class,
        ]);
        $this->add([
            'name' => self::HAS_ORIGINAL,
            'attributes' => [
            ],
            'options' => [
                'label' => 'Original page',
                'label_attributes' => [
                    'class' => 'control-label'
                ],
            ],
            'type' => Element\Checkbox::class,
        ]);                
        $this->add([
            'name' => self::ORIGINAL_ID,
            'attributes' => [
                'class' => 'form-control',
            ],            
            'type' => Element\Hidden::class,
        ]);        
        $sites = [];
        foreach ($siteService->findAll() as $site) {
            $sites[$site->getId()] = $site->getEnglishName();
        }
        $this->add([
            'name' => self::ORIGINAL_SITE,
            'options' => [
                'label' => 'Branch*',
                'label_attributes' => [
                    'class' => 'control-label'
                ],                
                'value_options' => $sites,
            ],
            'attributes' => [
                'class' => 'form-control',
            ],            
            'type' => Element\Select::class,
        ]);
        $this->add([
            'name' => self::ORIGINAL_PAGE,
            'options' => [
                'label' => 'Page*',
                'label_attributes' => [
                    'class' => 'control-label'
                ]                
            ],
            'attributes' => [
                'class' => 'form-control',
            ],            
            'type' => Element\Text::class,
        ]);
        $this->add([
            'type' => Element\Collection::class,
            'name' => self::CONTRIBUTORS,
            'options' => [
                'label' => 'Contributors',
                'count' => 0,
                'should_create_template' => true,
                'allow_add' => true,
                'target_element' => [
                    'type' => PageReportContributorFieldset::class,
                ],
            ],
        ]);                
        $this->add([
            'name' => self::USER_ADD,
            'options' => [
                'label' => 'Add'
            ],
            'attributes' => [
                'value' => 'Add',
                'class' => 'btn btn-default contributor-add',
                'aria-label' => 'Add contributor',
            ],
            'type' => Element\Button::class,
        ]);          
    }
    
    /**
     * @return array
     */
    public function getInputFilterSpecification()
    {
        return [
            self::PAGE_ID => [
                'required' => true,
                'validators' => [
                    new \Zend\Validator\Digits()
                ]
            ], 
            self::HAS_ORIGINAL => [
                'allow_empty' => true,
            ],
            self::ORIGINAL_SITE => [
                'allow_empty' => true,
            ],            
            self::ORIGINAL_ID => [
                'allow_empty' => true,
                'validators' => [
                    new \Zend\Validator\Digits()
                ]                
            ]
        ];
    }    
}
