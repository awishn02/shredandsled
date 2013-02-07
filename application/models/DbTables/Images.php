<?php
class Application_Model_DbTables_Images extends Application_Model_DbTable
{
    protected $_name = 'images';
    
    public function saveImage($image_data){
        return $this->insert($image_data);
    }
    
    public function getAllImages(){
        return $this->fetchAll();
    }
}