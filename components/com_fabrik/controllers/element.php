<?php
/**
 * Fabrik Element Controller
 *
 * @package     Joomla
 * @subpackage  Fabrik
 * @copyright   Copyright (C) 2005-2015 fabrikar.com - All rights reserved.
 * @license     GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

use Fabrik\Admin\Models\Lizt;
require 'controller.php';

/**
 * Fabrik Element Controller
 *
 * @package     Joomla
 * @subpackage  Fabrik
 * @since       1.5
 */
class FabrikControllerElement extends FabrikController
{
	/**
	 * Is the view rendered from the J content plugin
	 *
	 * @var  bool
	 */
	public $isMambot = false;

	/**
	 * Should the element be rendered as readonly
	 *
	 * @var  string
	 */
	public $mode = false;

	/**
	 * Id used from content plugin when caching turned on to ensure correct element rendered
	 *
	 * @var  int
	 */
	public $cacheId = 0;

	/**
	 * Display the view
	 *
	 * @return  null
	 */

	public function display()
	{
		$document = JFactory::getDocument();
		$input    = $this->input;
		$viewName = $input->get('view', 'element', 'cmd');
		$viewType = $document->getType();

		// Set the default view name from the Request
		$view = &$this->getView($viewName, $viewType);

		// $$$ rob 04/06/2011 don't assign a model to the element as its only a plugin

		$view->editable = ($this->mode == 'readonly') ? false : true;

		// Display the view
		$view->error = $this->getError();

		return $view->display();
	}

	/**
	 * Save an individual element value to the fabrik db
	 * used in inline edit table plugin
	 *
	 * @return  null
	 */
	public function save()
	{
		$listModel = new Lizt;
		$listModel->setId($this->input->getString('listid'));
		$rowId = $this->input->get('rowid');
		$key   = $this->input->get('element');
		$key   = array_pop(explode('___', $key));
		$value = $this->input->get('value');
		$listModel->storeCell($rowId, $key, $value);
		$this->mode = 'readonly';
		$this->display();
	}
}
