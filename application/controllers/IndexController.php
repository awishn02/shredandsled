<?php

class IndexController extends Base_Controller_Action
{
    public $uses = array('News');
    
    public function init()
    {
        /* Initialize action controller here */
        $this->js_files[] = 'jquery/jquery-1.8.2.min';
        $this->js_files[] = 'slider/jquery.ba-cond.min';
        $this->js_files[] = 'slider/modernizr.custom.79639';
        $this->js_files[] = 'slider/jquery.slitslider';
        $this->js_files[] = 'news_table';
        $this->css_files[] = 'slider/style';
        $this->css_files[] = 'slider/custom';
        parent::init();
    }

    public function indexAction()
    {
        // action body
        $this->view->assign('index', true);
        $news_tbl = $this->models['News'];
        $news = $news_tbl->getPosts();
        $news = $this->modelrow_object_to_array($news);
        $news = array_reverse($news);
        $news_json = $this->jsonencode($news);
        $this->view->assign('news_json', $news_json);
        $this->view->assign('pageTitle', 'Tufts Shred and Sled | Home');
    }
    
    public function homeAction()
    {
        $this->view->assign('pageTitle', 'Tufts Shred and Sled | Home');     
    }
}

