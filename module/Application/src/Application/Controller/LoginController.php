<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Service\HubServiceInterface;
use Application\Form\LoginForm;

/**
 * Description of LoginController
 *
 * @author Alexander
 */
class LoginController extends AbstractActionController
{
    /**
     *
     * @var LoginForm
     */
    protected $loginForm;
    
    public function __construct(HubServiceInterface $hubService, LoginForm $loginForm) 
    {
        $this->services = $hubService;
        $this->loginForm = $loginForm;
    }
    
    public function validateCredentials($hash, $password)
    {
        return password_verify($password, $hash);
    }
    
    public function loginAction()
    {        
        $request = $this->getRequest();
        $auth = $this->getServiceLocator()->get('Zend\Authentification\AuthentificationService');
        $failed = false;
        if ($request->isPost()) {
            $this->loginForm->setData($request->getPost());
            $user = $this->loginForm->get(LoginForm::USER_FIELD_NAME)->getValue();
            $pass = $this->loginForm->get(LoginForm::PASSWORD_FIELD_NAME)->getValue();
            
            if (!preg_match('/^[\da-z_]+$/i', $user)) {
                die();
                $failed = true;
            } else {
                $auth->getAdapter()
                        ->setIdentity($user)
                        ->setCredential($pass);
                $res = $auth->authenticate();
                if ($res->isValid()) {
                    $redirect = $this->loginForm->get(LoginForm::REDIRECT_FIELD_NAME)->getValue();
                    if ($redirect) {
                        return $this->redirect()->toUrl($redirect);
                    } else {
                        return $this->redirect()->toRoute('home');
                    }
                } else {
                    $failed = true;
                }
            }
        } else if ($request->isGet()) {
            $redirect = $request->getQuery('redirect', '');
            if ($auth->hasIdentity()) {
                if ($redirect) {
                    return $this->redirect()->toUrl($redirect);
                } else {
                    return $this->redirect()->toRoute('home');
                }
            }
            $this->loginForm->get(LoginForm::REDIRECT_FIELD_NAME)->setValue($redirect);
        }
        return new ViewModel([
            'form' => $this->loginForm,
            'failed' => $failed]);
    }
    
    /*
    public function signup()
    {
        $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        $sql = new Sql\Sql($dbAdapter);
        $insert = $sql->insert();
        $insert->into('scpper_users')
            ->values(['user' => 'admin', 
                'email' => 'lixbart@mail.ru', 
                'password' => password_hash('test', PASSWORD_DEFAULT)
                ]);
        $stmt = $sql->prepareStatementForSqlObject($insert);
        $stmt->execute();
        return $this->redirect()->toRoute('home');       
    }
    */ 
}
