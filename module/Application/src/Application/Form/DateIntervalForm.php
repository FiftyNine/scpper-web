<?php

namespace Application\Form;

use Zend\Form\Form;

class DateIntervalForm extends Form
{
    const FROM_DATE_NAME = 'from-date';
    const TO_DATE_NAME = 'to-date';
    const SUBMIT_NAME = 'submit';
    
    public function __construct($name = null, $options = [])
    {        
        
        parent::__construct($name, $options);
        
        $this->add([
            'type' => 'Zend\Form\Element\Text',
            'name' => self::FROM_DATE_NAME,
            'options' => [
                'label' => 'From',
//              'format' => 'Y-m-d'
            ],
            'attributes' => [
                'class' => 'form-control datepicker',
                'id' => 'date-interval-from'
//              'min' => '2000-01-01',
//              'max' => '2020-01-01',
//              'step' => '1', // days; default step interval is 1 day
            ]
        ]);
        $this->add([
            'type' => 'Zend\Form\Element\Text',
            'name' => self::TO_DATE_NAME,
            'options' => [
                'label' => 'To',
//              'format' => 'Y-m-d'
            ],
            'attributes' => [
                'class' => 'form-control datepicker',
                'id' => 'date-interval-to'
  //            'min' => '2000-01-01',
//              'max' => '2020-01-01',
//              'step' => '1', // days; default step interval is 1 day
            ]
        ]);
        $this->add([
            'type' => 'submit',
            'name' => self::SUBMIT_NAME,
            'attributes' => [
                'class' => 'btn btn-default',
                'id' => 'date-interval-button'
            ],
            'value' => 'Go',
        ]);        
    }    
}