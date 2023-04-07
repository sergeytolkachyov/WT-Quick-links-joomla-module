<?php
/**
 * @package     Wt Quick Links
 * @copyright   Copyright (C) 2022-2023 Sergey Tolkachyov. All rights reserved.
 * @author      Sergey Tolkachyov - https://web-tolk.ru
 * @link 		https://web-tolk.ru
 * @version 	1.4.6
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

use Joomla\CMS\Form\FormHelper;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\CMS\Version;

FormHelper::loadFieldClass('list');
class JFormFieldJshoppingcategories extends JFormFieldList
{

	protected $type = 'jshoppingcategories';

	protected function getOptions()
	{

		$options = array();
		if((new Version())->isCompatible('4.0') == true){

			if(!class_exists('JSHelper') && file_exists(JPATH_SITE . '/components/com_jshopping/bootstrap.php')){
				require_once(JPATH_SITE . '/components/com_jshopping/bootstrap.php');
			} elseif(!file_exists(JPATH_SITE . '/components/com_jshopping/bootstrap.php')) {
				return '<span class="badge badge-danger bg-danger">-- JoomShopping component is not installled --</span>';
			}

		} else {
			if(file_exists(JPATH_SITE . '/components/com_jshopping/lib/factory.php')){
				require_once (JPATH_SITE . '/components/com_jshopping/lib/factory.php');
				require_once (JPATH_SITE . '/components/com_jshopping/lib/functions.php');
			} else {
				return '<span class="badge badge-danger bg-danger">-- JoomShopping component is not installled --</span>';
			}
		}


		if((new Version())->isCompatible('4.0') == true){
			$allcats = \JSHelper::buildTreeCategory(0);
		} else {
			define('_JSHOP_CATEGORY','Category');
			$allcats = buildTreeCategory(0);
		}

			foreach ($allcats as $category)
			{
				if($category->category_id == 0){
					unset($category);
				} else{
					$options[] = HTMLHelper::_('select.option', $category->category_id, $category->name);
				}
			}

		return $options;

	}
}
?>