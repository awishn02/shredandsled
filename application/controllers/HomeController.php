<?php

class HomeController extends Base_Controller_Action{
    public $uses = array('News', 'Mailing');
    
    public function init(){
        $this->js_files[] = 'news_table';
        parent::init();
    }
    
    public function indexAction(){
        $news_tbl = $this->models['News'];
        $news = $news_tbl->getPosts();
        $news = $this->modelrow_object_to_array($news);
        $news = array_reverse($news);
        $news_json = $this->jsonencode($news);
        $this->view->assign('news_json', $news_json);
        $this->view->assign('pageTitle', 'Tufts Shred and Sled | Home');
        if($this->getRequest()->isPost()){
            $params = $this->_request->getParams();
            $action = $params['shred_action'];
            switch($action) {
                case "post":
                    $post_data = array('post' => stripslashes(nl2br($params['comment'])), 'quote' => stripslashes($params['quote']),
                               'quote_author' => stripslashes($params['quote_author']));
                    $news_tbl = $this->models['News'];
                    $news_tbl->savePost($post_data);
                    $this->_redirect("/home");
                    break;
                    
                case "mailing":
                    $user_data = array('first_name' => $params['first_name'],
                                       'last_name' => $params['last_name'],
                                       'email' => $params['email']);
                    $mailing_tbl = $this->models['Mailing'];
                    if(!$mailing_tbl->insertRow($user_data)){
                        $this->view->assign('email_exists', true);
                    }else{
                        $this->_redirect("/home");
                    }
                    break;
            }
        }
    }
    
    public function newsTableAction(){
        if($this->getRequest()->isPost()){
            $params = $this->getRequest()->getParams();
            $news = $params['news'];
            $page = $params['page'];
            $total_pages = $params['total_pages'];
            $this->view->assign('news', $news);
            $this->view->assign('page', $page);
            $this->view->assign('total_pages', $total_pages);
            Zend_Controller_Action_HelperBroker::removeHelper('Layout');
        }
    }
}