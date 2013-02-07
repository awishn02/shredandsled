<?php
/**
 * @see Zend_Validate_Abstract
 */
require_once 'Zend/Validate/Abstract.php';

class Zend_Validate_IsSubset extends Zend_Validate_Abstract
{
	
	protected $_emptyValidKeys = array("No_Answer", "Rather_not_say");
	
    const NOT_IN_ARRAY = 'notInArray';

    /**
     * @var array
     */
    protected $_messageTemplates = array(
        self::NOT_IN_ARRAY => "'%value%' was not found in the accepted list of values.",
    );

    /**
     * Haystack of possible values
     *
     * @var array
     */
    protected $_haystack;

    public function __construct($options)
    {
        $this->setHaystack($options['haystack']);
    }

    /**
     * Returns the haystack option
     *
     * @return mixed
     */
    public function getHaystack()
    {
        return $this->_haystack;
    }

    /**
     * Sets the haystack option
     *
     * @param  mixed $haystack
     * @return Zend_Validate_InArray Provides a fluent interface
     */
    public function setHaystack(array $haystack)
    {
        $this->_haystack = $haystack;
        return $this;
    }
    
    protected function _createMessage($messageKey, $value)
    {
    	return "list " . implode(",", $value) . " has one or more incorrect values";
    }

    /**
     * Defined by Zend_Validate_Interface
     *
     * Returns true if and only if $value is contained in the haystack option.
     *
     * @param  mixed $value
     * @return boolean
     */
    public function isValid($value)
    {
    	/**
    	 * TODO: ensure this logic is correct for all tables.  (I know it won't be)
    	 * this takes care of us picking 'No Answer', 'Rather not say', for people that 
    	 * don't select a value, but also allowing them to have a null column value
    	 */
    	if (is_null($value)) {
    		if (in_array(current($this->getHaystack()), $this->_emptyValidKeys)) {
    			$value = array(current($this->getHaystack()));
    		} else {
    			$haystack = $this->getHaystack();
    			$haystack[] = NULL;
    			$this->setHaystack($haystack);
    			$value = array(NULL);
    		}
    	} elseif (!is_array($value)) {
    		$value = array($value);
    	}
        $this->_setValue($value);
        
		if (count(array_intersect($this->getHaystack(), $value)) == count($value)) {
			return true;
		}
		
        $this->_error(self::NOT_IN_ARRAY);
        return false;
    }
}
