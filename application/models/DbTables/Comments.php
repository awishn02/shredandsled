<?php
class Application_Model_DbTables_Comments extends Application_Model_DbTable
{
    protected $_name = 'comments';
    private $comment_active = 1;
    private $comment_deleted = 2;
    
    public function saveComment($comment_data)
    {
        return $this->insert($comment_data);
    }
    
    public function getAllMainComments()
    {
        $where = $this->getAdapter()->quoteInto('reply_to = ? ', 0);
        $where .= $this->getAdapter()->quoteInto('AND status_id = ?', $this->comment_active);
        return $this->fetchAll($where);
    }
    
    public function getReplys($id)
    {
        $where = $this->getAdapter()->quoteInto('reply_to = ?', $id);
        $where .= $this->getAdapter()->quoteInto('AND status_id = ?', $this->comment_active);
        return $this->fetchAll($where);
    }
    
    public function deleteComment($id){
        $where = $this->getAdapter()->quoteInto('id = ?', $id);
        $save_data = array('status_id' => $this->comment_deleted);
        return $this->update($save_data, $where);
    }
}