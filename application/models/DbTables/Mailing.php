<?php
class Application_Model_DbTables_Mailing extends Application_Model_DbTable
{
   protected $_name = 'mailing';
   
   public function insertRow($user){
    $where = $this->getAdapter()->quoteInto('email = ?', $user['email']);
    $row = $this->fetchRow($where);
    if($row){
        return false;
    }
    return $this->insert($user);
   }
}