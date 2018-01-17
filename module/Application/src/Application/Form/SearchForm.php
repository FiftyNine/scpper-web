<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilterProviderInterface;

/**
 * Description of SearchForm
 *
 * @author Alexander
 */
class SearchForm extends Form implements InputFilterProviderInterface
{
    const TEXT_FIELD_NAME = 'search-text';
    const SITE_FIELD_NAME = 'search-site-id';
    const ALL_BRANCHES_NAME = 'all-branches';
    const WITH_DELETED_NAME = 'with-deleted';
    const BUTTON_NAME = 'search-button';
    
    public function __construct($name = null, $options = [])
    {
        parent::__construct($name, $options);
        $this->setAttribute("class", $this->getAttribute("class")." form-inline");
        $this->add([
            'name' => self::TEXT_FIELD_NAME,
            'attributes' => [
              'id' => self::TEXT_FIELD_NAME,
              'class'   => 'form-control search-control',
              'placeholder' => 'Search...'
            ],
            'type' => 'text'
        ]);
        $this->add([
            'name' => self::SITE_FIELD_NAME,
            'attributes' => [
                'id' => self::SITE_FIELD_NAME,
                'value' => '',
                'hidden' => ''
            ],            
        ]);
        $this->add([
            'type' => 'Zend\Form\Element\Checkbox',
            'name' => self::ALL_BRANCHES_NAME,
            'options' => [
                'label' => 'All branches',
                'use_hidden_element' => true,
                'checked_value' => '1',
                'unchecked_value' => '0',                
            ]
        ]);
        $this->add([
            'type' => 'Zend\Form\Element\Checkbox',
            'name' => self::WITH_DELETED_NAME,
            'options' => [
                'label' => 'With deleted',
                'use_hidden_element' => true,
                'checked_value' => '1',
                'unchecked_value' => '0',
            ]
        ]);
        $this->add([
            'type' => 'submit',
            'name' => self::BUTTON_NAME,
            'attributes' => [
                'class' => 'btn btn-default',
                'value' => 'Go',
            ],
        ]);
    }
    
    /**
     * @return array
     */
    public function getInputFilterSpecification()
    {
        return [
            self::ALL_BRANCHES_NAME => [
                'required' => false,
            ], 
            self::WITH_DELETED_NAME => [
                'required' => false,
            ]
        ];
    }       
}
