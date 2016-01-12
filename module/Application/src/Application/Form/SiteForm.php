<?php

namespace Application\Form;

use Zend\Form\Form;

class SiteForm extends Form
{
    
    const SITE_SELECTOR_NAME = 'current-site';
    
    public function __construct($name = null, $options = array())
    {
        parent::__construct($name, $options);
        
        $this->add(array(
            'name' => self::SITE_SELECTOR_NAME,
            'options' => array(
                'label' => 'Site', 
                'label_attributes' => array(
                    'class'  => 'control-label'
                )                
            ),
            'attributes' => array(
              'id' => self::SITE_SELECTOR_NAME,
              'class'   => 'form-control'
            ),
            'type' => 'select'
        ));
    }
}