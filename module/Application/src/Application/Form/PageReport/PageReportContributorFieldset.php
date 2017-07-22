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
use Application\Model\PageReportContributor;
use Application\Utils\AuthorRole;

/**
 * Description of PageReportContributorFieldSet
 *
 * @author Alexander
 */
class PageReportContributorFieldset extends Fieldset implements InputFilterProviderInterface
{
    const USER_ID = 'user-id';
    const USER_NAME = 'user-name';
    const ROLE = 'role';
    const USER_DELETE = 'user-delete';
    
    public function __construct()
    {                
        parent::__construct('contributor');
        $this->setAttribute('class', 'page-report-contributor');
        $this->add([
            'name' => self::USER_ID,
            'attributes' => [
                'required' => 'required',
                'class' => 'form-control',
            ],
            'type' => Element\Hidden::class,
        ]);        
        $this->add([
            'name' => self::USER_NAME,
            'options' => [
                'label' => 'User*',
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
            'name' => self::ROLE,
            'options' => [
                'label' => 'Role*',
                'value_options' => AuthorRole::ROLE_DESCRIPTIONS,
                'label_attributes' => [
                    'class' => 'control-label'
                ]                
            ],
            'attributes' => [
                'required' => 'required',
                'class' => 'form-control contributor-role',
            ],
            'type' => Element\Select::class
        ]);        
        $this->add([
            'name' => self::USER_DELETE,
            'options' => [
                'label' => 'Delete'
            ],
            'attributes' => [
                'value' => 'Delete',
                'class' => 'btn btn-default contributor-delete',
                'aria-label' => 'Delete',
            ],
            'type' => Element\Button::class,
        ]);
        $hydrator = new \Zend\Stdlib\Hydrator\ClassMethods(false);
        $nameMap = new \Zend\Stdlib\Hydrator\NamingStrategy\MapNamingStrategy([
            self::USER_ID => 'userId',
            self::USER_NAME => 'userName',
            self::ROLE => 'role'
        ]);
        $hydrator->setNamingStrategy($nameMap);
        $this->setHydrator($hydrator);
        $this->setObject(new PageReportContributor());
    }

    /**
     * @return array
     */
    public function getInputFilterSpecification()
    {
        return [
            'user-id' => [
                'required' => true,
                'validators' => [
                    new \Zend\Validator\Digits()
                ]
            ],
            'role' => [
                'required' => true,
            ],            
        ];
    }
}
