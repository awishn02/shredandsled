<?php
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
	protected function _initDocType()
	{
		$this->bootstrap('view');
		$view = $this->getResource('view');
		$view->doctype("XHTML1_STRICT");
	}

	public function _initAutoLoad()
	{
		require_once 'Zend/Loader/Autoloader.php';
		$loader = Zend_Loader_Autoloader::getInstance();
		$loader->registerNamespace('Base_');
	}
	
	/**
	 * Begin ===============================PadMatcher Edits=============================
	 * 
	 * defines constants from the application ini file.
	 * @param arr $constants
	 * @return void
	 */
	protected function setconstants($constants)
	{
		foreach ($constants as $key=>$value){
			if(!defined($key)){
				define($key, $value);
			}
		}
	}
	
	/**
	 * 
	 * 
	 * * End ===============================PadMatcher Edits=============================
	 */

}