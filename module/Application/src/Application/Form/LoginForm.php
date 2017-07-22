<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Form;

use Zend\Form\Form;

/**
 * Description of LoginForm
 *
 * @author Alexander
 */
class LoginForm extends Form
{
    const USER_FIELD_NAME = 'user-input';
    const PASSWORD_FIELD_NAME = 'password-input';
    const REDIRECT_FIELD_NAME = 'redirect-url';
    const BUTTON_NAME = 'login-button';
    
    public function __construct($name = null, $options = array())
    {
        parent::__construct($name, $options);
        $this->setAttribute("class", $this->getAttribute("class")." form-horizontal top-buffer");
        $this->add([
            'name' => self::USER_FIELD_NAME,
            'attributes' => [
                'id' => self::USER_FIELD_NAME,
                'class'   => 'form-control',
                'placeholder' => 'User'
            ],
            'options' => [
                'label' => 'User',
                'label_attributes' => [
                    'class' => 'control-label col-sm-offset-2 col-sm-2'
                ]
            ],
            'type' => 'text'
        ]);
        $this->add([
            'name' => self::PASSWORD_FIELD_NAME,
            'attributes' => [
                'id' => self::PASSWORD_FIELD_NAME,
                'class'   => 'form-control',
                'placeholder' => 'Password'
            ],
            'options' => [
                'label' => 'Password',
                'label_attributes' => [
                    'class' => 'control-label col-sm-offset-2 col-sm-2'
                ]
            ],            
            'type' => 'password'
        ]);
        $this->add([
            'name' => self::REDIRECT_FIELD_NAME,
            'attributes' => [
                'id' => self::REDIRECT_FIELD_NAME,
                'class'   => 'form-control',
            ],
            'type' => 'hidden',
        ]);        
        $this->add([
            'type' => 'submit',
            'name' => self::BUTTON_NAME,
            'attributes' => [
                'class' => 'btn btn-default',
                'value' => 'Sign in',
            ],                 
        ]);        
    }    
}
