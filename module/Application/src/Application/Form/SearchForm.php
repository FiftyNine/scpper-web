<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Form;

use Zend\Form\Form;

/**
 * Description of SearchForm
 *
 * @author Alexander
 */
class SearchForm extends Form
{
    const TEXT_FIELD_NAME = 'search-text';
    const SITE_FIELD_NAME = 'search-site-id';
    const BUTTON_NAME = 'search-button';
    
    public function __construct($name = null, $options = array())
    {
        parent::__construct($name, $options);
        $this->setAttribute("class", $this->getAttribute("class")." form-inline");
        $this->add(array(
            'name' => self::TEXT_FIELD_NAME,
            'attributes' => array(
              'id' => self::TEXT_FIELD_NAME,
              'class'   => 'form-control search-control',
              'placeholder' => 'Search...'
            ),
            'type' => 'text'
        ));
        $this->add(array(
            'name' => self::SITE_FIELD_NAME,
            'attributes' => array(
                'id' => self::SITE_FIELD_NAME,
                'value' => '',
                'hidden' => ''
            ),            
        ));                
        $this->add(array(
            'type' => 'submit',
            'name' => self::BUTTON_NAME,
            'attributes' => array(
                'class' => 'btn btn-default',
                'value' => 'Go',
            ),            
        ));        
    }
}
