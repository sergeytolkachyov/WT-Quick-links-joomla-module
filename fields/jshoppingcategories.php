<?php
/**
 * @package     WT JoomShopping B24 PRO
 * @version     2.5.2
 * @Author Sergey Tolkachyov, https://web-tolk.ru
 * @copyright   Copyright (C) 2020 Sergey Tolkachyov
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
 * @since       2.2.0
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
		if((new Version())->isCompatible('4.0') == true){

			if(!class_exists('JSHelper')){
				require_once(JPATH_SITE . '/components/com_jshopping/bootstrap.php');
			}
			$allcats = \JSHelper::buildTreeCategory(0);
		} else {
			require_once (JPATH_SITE . '/components/com_jshopping/lib/factory.php');
			require_once (JPATH_SITE . '/components/com_jshopping/lib/functions.php');
			define('_JSHOP_CATEGORY','Category');
			$allcats = buildTreeCategory(0);
		}

		$options = array();
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