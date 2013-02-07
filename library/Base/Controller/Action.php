<?php

require_once 'Zend/Controller/Action.php';

abstract class Base_Controller_Action extends Zend_Controller_Action {
    public $js_files = array('jquery/jquery', 'bootstrap', 'jquery/jqueryui', 'jquery.colorbox-min', 'jquery/jquery.filedrop');
    public $css_files = array('bootstrap', 'bootstrap-responsive', 'jquery-ui-1.8.17.custom', 'shredandsled');
    public $model_prefix = "Application_Model_DbTables_";
    public $models = array();
    
    public function init()
    {   
	if (Zend_Auth::getInstance()->hasIdentity())
        {
            $authStorage = Zend_Auth::getInstance()->getStorage();
            $userInfoFromStorage = $authStorage->read();
            $this->view->assign('logged_in', true);
            $this->view->assign('user', $userInfoFromStorage->user);
        }
        foreach ($this->uses as $model_name)
        {
            $model = $this->model_prefix . $model_name;
            $this->models[$model_name] = new $model;
        }
    }

    public function postDispatch()
    {
        $view = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer')->view;
        $view->js_files = $this->js_files;
        $view->css_files = $this->css_files;

        parent::postDispatch();
    }
    
    public function set_session_var($session_name, $data, $onekey=null)
    {
        $session = new Zend_Session_Namespace($session_name);
        if (is_null($onekey))
        {
            foreach($data as $key=>$value)
            {
                $session->$key = $value;
            }
        }
        else
        {
            $session->$onekey = $data;
        }

        return true;
    }

    public function read_session_var($session_name, $onekey=null)
    {
        $session = new Zend_Session_Namespace($session_name);
        if (!is_null($onekey)) {
            return $session->$onekey;
        }
        return $session;
    }

    public static function modelrow_object_to_array($rowobj)
    {
        $data = (array) $rowobj;
        return current($data);
    }
    
    public function jsonencode($data)
    {
        return addslashes(json_encode($data));
    }
}