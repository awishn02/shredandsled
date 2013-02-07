<?php 

require_once 'Zend/Validate/Abstract.php';

class Zend_Validate_MatchesValue extends Zend_Validate_Abstract 
{
	public $value_to_match;
	protected $_messages = array();
	
	public function __construct($value_to_match)
	{
		$this->value_to_match = $value_to_match;
	}
	
	public function isValid($value)
	{
		$r = (bool)$value == $this->value_to_match;
		if (!$r) {
			$this->_messages[] = "Does not match";
		}
		return $r;
	}
	
	public function getMessages()
	{
		return $this->_messages;
	}
	
}


?>