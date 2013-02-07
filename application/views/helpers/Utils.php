<?php


require_once 'Zend/View/Helper/Abstract.php';

class Zend_View_Helper_Utils extends Zend_View_Helper_Abstract
{
    public $uses = array('Comments');
    
    public function utils()
    {
        $this->baseUrl = Zend_Controller_Front::getInstance()->getBaseUrl();
        return $this;
    }
    
    public static function format_date($date_str, $format="F jS, Y")
    {
        if ($date_str && $date_str != "null")
        {
            if (!self::check_for_edit_profile_text($date_str))
                return date($format, strtotime($date_str));
            else
                return $date_str;
        }
        return "N/A";
    }
    
    public static function check_for_edit_profile_text($text)
    {
        return strpos($text, "N/A") > -1 && strpos($text, "edit") > -1;
    }
    
    public function getReplies($id)
    {
        $comments = new Application_Model_DbTables_Comments;
        return $comments->getReplys($id);
    }
}