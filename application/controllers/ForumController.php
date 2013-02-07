<?php

class ForumController extends Base_Controller_Action{
    public $uses = array('Comments');
    
    public function init(){
        $this->view->assign('pageTitle', 'Tufts Shred and Sled | Forum');
        $this->js_files[] = 'forum_table';
        parent::init();
    }
    
    public function indexAction(){
        $comments_tbl = $this->models['Comments'];
        $comments = $comments_tbl->getAllMainComments();
        $comments = $this->modelrow_object_to_array($comments);
        $comments = array_reverse($comments);
        $comments_json = $this->jsonencode($comments);
        $this->view->assign('comments_json', $comments_json);
        
    }
    
    public function forumTableAction(){
        if($this->getRequest()->isPost() && $this->getRequest()->isXmlHttpRequest()){
            $params = $this->getRequest()->getParams();
            $posts = $params['posts'];
            $page = $params['page'];
            $total_pages = $params['total_pages'];
            $this->view->assign('comments', $posts);
            $this->view->assign('page', $page);
            $this->view->assign('total_pages', $total_pages);
            $this->view->assign('table', true);
            Zend_Controller_Action_HelperBroker::removeHelper('Layout');
        }
    }
    
    public function postAction(){
        if($this->getRequest()->isPost() && $this->getRequest()->isXmlHttpRequest())
        {
            $params = $this->getRequest()->getParams();
            $comments_tbl = $this->models['Comments'];
            $comment = array('title' => stripslashes($params['title']),
                            'name' => stripslashes($params['name']),
                            'comment' => stripslashes(nl2br($params['comment'])),
                            'reply_to' => $params['reply_to']);
            $comments_tbl->saveComment($comment);
            $this->_helper->viewRenderer->setNoRender(true);
        }   
    }
    
    public function deletePostAction(){
        if($this->getRequest()->isPost())
        {
            $params = $this->getRequest()->getParams();
            $comments_tbl = $this->models['Comments'];
            $comments_tbl->deleteComment($params['id']);
            $this->_helper->viewRenderer->setNoRender(true);
        }
    }
}