<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_wt_quick_links
 *
 * @copyright   Copyright (C) 2021 Sergey Tolkachyov. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
use \Joomla\CMS\Router\Route;
use Joomla\CMS\Factory;
defined('_JEXEC') or die;

/**
 * Helper for mod_wt_quick_links
 *
 * @since  1.0.0
 */
class ModWTQuickLinks
{
	/**
	 * Retrieve list of module entries
	 *
	 * @param   \Joomla\Registry\Registry  &$params  module parameters
	 *
	 * @return  array
	 *
	 * @since   1.0.0
	 */
	public static function getList(&$params)
	{
		$link_list = array();
		$link = array();
		foreach ($params->get('fields') as $field){

			$link['link_text'] = $field->link_text;
			$link['link_image'] = $field->link_image;
			$link['link_icon_css'] = $field->link_icon_css;
			$link['link_additional_text'] = $field->link_additional_text;

			if($field->link_type == 'com_jshopping'){
				$link['url'] = Route::_('index.php?option=com_jshopping&controller=category&task=view&category_id='.$field->jshoppingcategories);
				$link_list[] = (object)$link;
			}elseif ($field->link_type == 'com_virtuemart'){
				// Убираем "верхний уровень категории с virtuemart_category_id=0
				if(!$field->virtuemartcategories == '0'){
					$link['url'] = Route::_('index.php?option=com_virtuemart&view=category&virtuemart_category_id='.$field->virtuemartcategories);
					$link_list[] = (object)$link;
				}

			}elseif ($field->link_type == 'com_content'){
				$link['url'] = Route::_('index.php?option=com_content&view=category&id='.$field->contentcategories);
				$link_list[] = (object)$link;
			}elseif ($field->link_type == 'menuitem'){
				$menu_item = Factory::getApplication()->getMenu( 'site' )->getItem($field->menuitem);
				$link['url'] = Route::_($menu_item->link.'&Itemid='.$menu_item->id);
				$link_list[] = (object)$link;
			}elseif ($field->link_type == 'custom'){

				$link['url'] = $field->custom_link;
				$link_list[] = (object)$link;
			}
		}//end foreach
 		return (object)$link_list;
	}
}
