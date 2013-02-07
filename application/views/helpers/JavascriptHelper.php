<?php
/**
 *
 */
require_once 'Zend/View/Helper/Abstract.php';

/**
 * JavascriptHelper helper
 *
 * @uses viewHelper Zend_View_Helper
 */
class Zend_View_Helper_JavascriptHelper extends Zend_View_Helper_Abstract 
{
	CONST JS_FILE_SEP = '#';

	/**
	 * @var Zend_View_Interface 
	 */
	public $view;
	
	/**
	 * @var string
	 */
	protected $js_path = "js/";
	
	public function javascriptHelper($javascript_files) 
	{
		if (empty($javascript_files)) {
			return;
		}
		$js_files = array();
		foreach ($javascript_files as $js_file_name_unformatted)
		{
			$js_file_name = $this->_formatJSFileName($js_file_name_unformatted);
			$js_file = $this->js_path . $js_file_name;
		  	$this->view->headScript()->appendFile("/$js_file");
		}
	}
	
	/**
	* formats a javascript file name from the controller's array
	* 
	* @param js_fname string
	* @return js_file_name string
	*/
	protected function _formatJSFileName($js_fname)
	{
		if (strpos($js_fname, self::JS_FILE_SEP) === false) 
			$js_file_name = $js_fname;
		else
			$js_file_name = str_replace(self::JS_FILE_SEP, "/", $js_fname);
			
		return $js_file_name . ".js";
	}
	
	/**
	 * Sets the view field 
	 * @param $view Zend_View_Interface
	 */
	public function setView(Zend_View_Interface $view) 
	{
		$this->view = $view;
	}
}
