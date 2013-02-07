<?php
/**
 *
*/
require_once 'Zend/View/Helper/Abstract.php';

/**
 * CssHelper helper
 *
 * @uses viewHelper Zend_View_Helper
 */
class Zend_View_Helper_CssHelper extends Zend_View_Helper_Abstract
{
	CONST CSS_FILE_SEP = '#';

	/**
	 * @var Zend_View_Interface
	 */
	public $view;

	/**
	 * @var string
	 */
	protected $css_path = "css/";

	public function cssHelper($css_files)
	{
		if (empty($css_files)) {
			return;
		}
		$new_css_files = array();
		foreach ($css_files as $css_file_name_unformatted)
		{
			$css_file_name = $this->_formatCssFileName($css_file_name_unformatted);
			$css_file = $this->css_path . $css_file_name;
			$this->view->headLink()->appendStylesheet($this->view->baseUrl("/$css_file"));
		}
	}

	/**
	 * formats a css file name from the controller's array
	 *
	 * @param css_fname string
	 * @return css_file_name string
	 */
	protected function _formatCssFileName($css_fname)
	{
		if (strpos($css_fname, self::CSS_FILE_SEP) === false)
			$css_file_name = $css_fname;
		else
			$css_file_name = str_replace(self::CSS_FILE_SEP, "/", $css_fname);

		return $css_file_name . ".css";
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





?>