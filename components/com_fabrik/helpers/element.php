<?php
/**
 * Element Helper class
 *
 * @package     Joomla
 * @subpackage  Fabrik.helpers
 * @copyright   Copyright (C) 2005-2015 fabrikar.com - All rights reserved.
 * @license     GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

use Joomla\String\String;
use Fabrik\Helpers\ArrayHelper;
use Fabrik\Helpers\Worker;
use Fabrik\Admin\Models\Form;

/**
 * Element Helper class
 *
 * @package     Joomla
 * @subpackage  Fabrik.helpers
 * @since       3.0.6
 */

class FabrikHelperElement
{
	/**
	 * For processing repeat elements we need to make its
	 * ID element during the form process
	 *
	 * @param   plgFabrik_Element  $baseElement  repeat element (e.g. db join rendered as checkbox)
	 *
	 * @return  plgFabrik_ElementInternalid
	 */

	public static function makeIdElement($baseElement)
	{
		$pluginManager = Worker::getPluginManager();
		$groupModel = $baseElement->getGroupModel();
		$elementModel = $pluginManager->getPlugIn('internalid', 'element');
		$elementModel->getElement()->set('name', 'id');
		$elementModel->getParams()->set('repeat', $baseElement->isJoin());
		$elementModel->getElement()->set('group_id', $groupModel->getId());
		$elementModel->setGroupModel($baseElement->getGroupModel());
		$elementModel->_joinModel = $groupModel->getJoinModel();

		return $elementModel;
	}

	/**
	 * For processing repeat elements we need to make its
	 * parent id element during the form process
	 *
	 * @param   plgFabrik_Element  $baseElement  repeat element (e.g. db join rendered as checkbox)
	 *
	 * @return  plgFabrik_ElementField
	 */

	public static function makeParentElement($baseElement)
	{
		$pluginManager = Worker::getPluginManager();
		$groupModel = $baseElement->getGroupModel();
		$elementModel = $pluginManager->getPlugIn('field', 'element');
		$elementModel->getElement()->set('name', 'parent_id');
		$elementModel->getParams()->set('repeat', $baseElement->isJoin());
		$elementModel->getElement()->set('group_id', $groupModel->getId());
		$elementModel->setGroupModel($baseElement->getGroupModel());
		$elementModel->_joinModel = $groupModel->getJoinModel();

		return $elementModel;
	}

	/**
	 * Short cut for getting the element's filter value
	 *
	 * @param   int  $elementId  Element id
	 *
	 * @since   3.0.7
	 *
	 * @return  string
	 */

	public static function filterValue($elementId)
	{
		$app = JFactory::getApplication();
		$pluginManager = Worker::getPluginManager();
		$model = $pluginManager->getElementPlugin($elementId);
		$listModel = $model->getListModel();
		$listId = $listModel->getId();
		$key = 'com_fabrik.list' . $listId . '_com_fabrik_' . $listId . '.filter';
		$filters = ArrayHelper::fromObject($app->getUserState($key));
		$elementIds = (array) ArrayHelper::getValue($filters, 'elementid', array());
		$index = array_search($elementId, $elementIds);
		$value = $filters['value'][$index];

		return $value;
	}

	/**
	 * Is the key part of an element join's data. Used in csv import/export
	 *
	 * @param   Form  $model  Form model
	 * @param   string             $key  Key - full element name or full element name with _id / ___params appended
	 *
	 * @return boolean
	 */
	public static function keyIsElementJoinInfo(Form $model, $key)
	{
		$elementModel = self::findElementFromJoinKeys($model, $key);

		if ($elementModel && $elementModel->isJoin())
		{
			return true;
		}

		return false;
	}

	/**
	 * Find the element associated with a key.
	 * Loose lookup to find join element from any key related to the join (e.g. _id & __params).
	 * Used in csv import/export
	 *
	 * @param   Form  $model  Form model
	 * @param   string             $key    Key - full element name or full element name with _id / ___params appended
	 *
	 * @return  PlgFabrik_Element|boolean
	 */
	public static function findElementFromJoinKeys(Form $model, $key)
	{
		// Search on fullname fullname_id and fullname___params
		$lookups = array($key, substr($key, 0, String::strlen($key) - 3), substr($key, 0, String::strlen($key) - 9));

		foreach ($lookups as $lookup)
		{
			$elementModel = $model->getElement($lookup);

			if ($elementModel)
			{
				return $elementModel;
			}
		}

		return false;
	}
}
