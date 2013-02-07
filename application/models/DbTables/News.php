<?php
class Application_Model_DbTables_News extends Application_Model_DbTable
{
    protected $_name = 'news';
    private $news_active = 1;
    private $news_deleted = 2;
    
    public function savePost($post){
        return $this->insert($post);
    }
    
    public function getPosts(){
        $where = $this->getAdapter()->quoteInto("status_id = ?", $this->news_active);
        return $this->fetchAll($where);
    }
}