<?php

class PhotosController extends Base_Controller_Action{
    public $uses = array('Images');
    
    public function init(){
        $this->js_files[] = 'uploader';
        $this->css_files[] = 'uploader';
        $this->js_files[] = 'jquery.nivo.slider';
        $this->css_files[] = 'nivo-slider';
        parent::init();
    }
    
    public function indexAction(){
        $this->view->assign('pageTitle', 'Tufts Shred and Sled | Photos');
        $images_tbl = $this->models['Images'];
        $images = $images_tbl->getAllImages();
        $all_images = array();
        foreach($images as $image){
            $all_images[str_replace('+', " ", $image['title'])][] = $image['image_name'];
        }
        $this->view->assign('images', $all_images);
    }
    
    public function uploadAction(){
        
    }
    
    public function uploadHandlerAction(){
        if($this->getRequest()->isPost()){
            $params = $this->getRequest()->getParams();
            $title = $params['title'];
            $demo_mode = false;
            $upload_dir = '/Applications/MAMP/htdocs/shredandsled/public/img/photos/';
            $allowed_ext = array('jpg','jpeg','png','gif');
            
            if(strtolower($_SERVER['REQUEST_METHOD']) != 'post'){
                $this->exit_status('Error! Wrong HTTP method!');
            }
    
            if(array_key_exists('pic',$_FILES) && $_FILES['pic']['error'] == 0 ){
                $pic = $_FILES['pic'];
                
                include('resize-class.php');
    
                if(!in_array($this->get_extension($pic['name']),$allowed_ext)){
                	$this->exit_status('Only '.implode(',',$allowed_ext).' files are allowed!');
                }	
        
                if($demo_mode){
        
                    // File uploads are ignored. We only log them.
    
                    $line = implode('		', array( date('r'), $_SERVER['REMOTE_ADDR'], $pic['size'], $pic['name']));
                    file_put_contents('log.txt', $line.PHP_EOL, FILE_APPEND);
    
                    $this->exit_status('Uploads are ignored in demo mode.');
                }
    
                // Move the uploaded file from the temporary
                // directory to the uploads folder:
    
                if(move_uploaded_file($pic['tmp_name'], $upload_dir.$pic['name'])){
                        $image_data = array('title' => $title, 'image_name' => $pic['name']);
                        $image_tbl = $this->models['Images'];
                        $image_tbl->saveImage($image_data);
                        $resizeObj = new resize($upload_dir.$pic['name']);
                        $resizeObj->resizeImage(960, 540, 'crop');
                        $resizeObj->saveImage($upload_dir.$pic['name'], 100);
                	$this->exit_status('File was uploaded successfuly!');
                }
            }
    
            $this->exit_status('Something went wrong with your upload!');
        }
    }

    public function exit_status($str){
	echo json_encode(array('status'=>$str));
	exit;
    }

    public function get_extension($file_name){
	$ext = explode('.', $file_name);
	$ext = array_pop($ext);
	return strtolower($ext);
    }
}