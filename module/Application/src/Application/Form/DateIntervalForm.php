<?php

namespace Application\Form;

use Zend\Form\Form;

class DateIntervalForm extends Form
{
    const FROM_DATE_NAME = 'from-date';
    const TO_DATE_NAME = 'to-date';
    const SUBMIT_NAME = 'submit';
    
    public function __construct($name = null, $options = array())
    {        
        
        parent::__construct($name, $options);
        
        $this->add(array(
            'type' => 'Zend\Form\Element\Date',
            'name' => self::FROM_DATE_NAME,
            'options' => array(
                    'label' => 'From',
                    'format' => 'Y-m-d'
            ),
            'attributes' => array(
                    'class' => 'form-control',
                    'min' => '2000-01-01',
                    'max' => '2020-01-01',
                    'step' => '1', // days; default step interval is 1 day
            )
        ));
        $this->add(array(
            'type' => 'Zend\Form\Element\Date',
            'name' => self::TO_DATE_NAME,
            'options' => array(
                    'label' => 'To',
                    'format' => 'Y-m-d'
            ),
            'attributes' => array(
                    'class' => 'form-control',
                    'min' => '2000-01-01',
                    'max' => '2020-01-01',
                    'step' => '1', // days; default step interval is 1 day
            )
        ));
        $this->add(array(
            'type' => 'submit',
            'name' => self::SUBMIT_NAME,
            'attributes' => array(
                    'class' => 'btn btn-default',
            ),
            'value' => 'Go',
        ));        
    }    
}