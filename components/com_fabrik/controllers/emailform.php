<?php
/**
 * Fabrik Email Form Controller
 *
 * @package     Joomla
 * @subpackage  Fabrik
 * @copyright   Copyright (C) 2005-2015 fabrikar.com - All rights reserved.
 * @license     GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

require 'controller.php';

/**
 * Fabrik Email Form Controller
 *
 * @static
 * @package     Joomla
 * @subpackage  Fabrik
 * @since       1.5
 */

class FabrikControllerEmailform extends FabrikController
{
	/**
	 * Display the view
	 *
	 * @param   boolean  $cachable    If true, the view output will be cached - NOTE not actually used to control caching!!!
	 * @param   array    $urlparams  An array of safe url parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
	 *
	 * @return  JController  A JController object to support chaining.
	 */

	public function display($cachable = false, $urlparams = array())
	{
		$document = JFactory::getDocument();
		$input = $this->input;
		$viewName = $input->get('view', 'emailform');
		$modelName = 'form';

		$viewType = $document->getType();

		// Set the default view name from the Request
		$view = $this->getView($viewName, $viewType);

		// FIXME for 3.5 - not display() anymore
		// Test for failed validation then page refresh
		$model = new Fabrik\Admin\Models\Form;
		$view->setModel($model, true);
		// Display the view
		$view->error = $this->getError();
		$view->display();

		return $this;
	}
}
