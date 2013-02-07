<?php

class LoginController extends Base_Controller_Action {
    public function init(){
	if (Zend_Auth::getInstance()->hasIdentity())
        {
            $this->view->assign('logged_in', true);
        }
    }
    
    public function indexAction(){
        $dbAdapter = Zend_Db_Table::getDefaultAdapter();
        $authAdapter = new Zend_Auth_Adapter_DbTable($dbAdapter);
        $authAdapter
            ->setTableName('users')
            ->setIdentityColumn('user')
            ->setCredentialColumn('password');
        if($this->getRequest()->isPost()){
            $info = $this->getRequest()->getParams();
            $user = $info['user'];
            $password = $info['password'];
            $authAdapter
                ->setIdentity($user)
                ->setCredential($password);

            $loginAuth = Zend_Auth::getInstance();
            $result = $loginAuth->authenticate($authAdapter);
            if($result->isValid()){
                $userData = $authAdapter->getResultRowObject(array('user'), 'password');
                $authStorage = $loginAuth->getStorage();
                $authStorage->write($userData);
                $this->_redirect('/home');
            }else{
                $this->view->assign('failed_login', true);
            }
        }
    }
}