<?php

class MusicController extends Base_Controller_Action{
    public function init(){
        
    }
    public function indexAction(){
        $this->view->assign('pageTitle', 'Tufts Shred and Sled | Music');
    }
}