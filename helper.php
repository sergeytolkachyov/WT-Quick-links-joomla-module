<?php
/**
 * @package     Wt Quick Links
 * @copyright   Copyright (C) 2022 Sergey Tolkachyov. All rights reserved.
 * @link 		https://web-tolk.ru
 * @version 	1.4.0
 * @license     GNU General Public License version 2 or later
 */

use Joomla\CMS\Language\Text;
use \Joomla\CMS\Router\Route;
use Joomla\CMS\Factory;
use Joomla\CMS\Version;

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
		$module_xml = simplexml_load_file(JPATH_SITE.'/modules/mod_wt_quick_links/mod_wt_quick_links.xml');
		Factory::getApplication()->getDocument()->addScriptOptions('mod_wt_quick_links',[
			'name' => Text::_((string)$module_xml->name),
			'type' => (string)$module_xml['type'],
			'version' => (string)$module_xml->version,
			'author' => (string)$module_xml->author,
			'authorUrl' => (string)$module_xml->authorUrl,
			'creationDate' => (string)$module_xml->creationDate,
		]);

		if ((new Version())->isCompatible('4.0') == true)
		{
			// Joomla 4
			$input = Factory::getApplication()->getInput();
		}
		else
		{
			// Joomla 3
			$input = Factory::getApplication()->input;
		}
		$itemId    = $input->get('Itemid');
		$option    = $input->get('option');
		$link_list = array();
		$link      = array();
		foreach ($params->get('fields') as $field)
		{

			$link['link_text']            = $field->link_text;
			$link['link_image']           = $field->link_image;
			$link['link_icon_css']        = $field->link_icon_css;
			$link['link_additional_text'] = $field->link_additional_text;
			$link['is_responsive_images']    = $field->is_responsive_images;
			$link['responsive_images']    = $field->responsive_images;
			$link['media_type']    = $field->media_type;
			$link['link_video']    = $field->link_video;
			$link['link_video_poster']    = $field->link_video_poster;
			$link['is_responsive_videos']    = $field->is_responsive_videos;
			$link['responsive_videos']    = (array)$field->responsive_videos;

			/**
			 * Условия исключения показа элемента
			 */
			if (@$field->exclude == 1)
			{
				// Есть условия исключения показа

				// Условие - категория JoomShopping

				if ($field->exclude_type == 'com_jshopping_categories' &&
					$option == 'com_jshopping' &&
					$input->get('controller') == 'category' &&
					in_array($input->get('category_id'), (array) $field->exclude_jshoppingcategories))
				{
					continue;
				}

				// Условие - пункт меню

				if ($field->exclude_type == 'menuitem' && in_array($itemId, (array) $field->exclude_menu_items))
				{
					continue;
				}

				// Условие - категория материалов Joomla

				if ($field->exclude_type == 'com_content_categories' &&
					$option == 'com_content' &&
					($input->get('view') == 'category' &&
						in_array($input->get('id'), (array) $field->exclude_contentcategories) ||
						$input->get('view') == 'article' &&
						in_array($input->get('catid'), (array) $field->exclude_contentcategories)
					)
				)
				{
					continue;
				}

			}
			/**
			 * Формируем ссылки
			 */

			if ($field->link_type == 'com_jshopping')
			{

				if ((new Version())->isCompatible('4.0') == true)
				{

					if (!class_exists('JSHelper') && file_exists(JPATH_SITE . '/components/com_jshopping/bootstrap.php'))
					{
						require_once(JPATH_SITE . '/components/com_jshopping/bootstrap.php');
					}
					elseif(!file_exists(JPATH_SITE . '/components/com_jshopping/bootstrap.php'))
					{
						continue;
					}

				}
				else
				{
					if (file_exists(JPATH_SITE . '/components/com_jshopping/lib/factory.php'))
					{
						require_once(JPATH_SITE . '/components/com_jshopping/lib/factory.php');
						require_once(JPATH_SITE . '/components/com_jshopping/lib/functions.php');
					}
					else
					{
						continue;
					}
				}

				$jshop_link = 'index.php?option=com_jshopping&controller=category&task=view&category_id=' . $field->jshoppingcategories;
				if ((new Version())->isCompatible('4.0') == true)
				{
					$jshop_itemid = \JSHelper::getDefaultItemid($jshop_link);
				}
				else
				{
					$jshop_itemid = getDefaultItemid($jshop_link);
				}


				$link['url'] = Route::_($jshop_link . '&Itemid=' . $jshop_itemid);

				$link_list[] = (object) $link;
			}
			elseif ($field->link_type == 'com_virtuemart')
			{
				// Убираем "верхний уровень категории с virtuemart_category_id=0
				if (!$field->virtuemartcategories == '0')
				{
					$link['url'] = Route::_('index.php?option=com_virtuemart&view=category&virtuemart_category_id=' . $field->virtuemartcategories);
					$link_list[] = (object) $link;
				}

			}
			elseif ($field->link_type == 'com_content')
			{
				$link['url'] = Route::_('index.php?option=com_content&view=category&id=' . $field->contentcategories);
				$link_list[] = (object) $link;
			}
			elseif ($field->link_type == 'menuitem')
			{
				$menu_item   = Factory::getApplication()->getMenu('site')->getItem($field->menuitem);
				$link['url'] = Route::_($menu_item->link . '&Itemid=' . $menu_item->id);
				$link_list[] = (object) $link;
			}
			elseif ($field->link_type == 'custom')
			{

				$link['url'] = $field->custom_link;
				$link_list[] = (object) $link;
			}
		}//end foreach
		return (object) $link_list;
	}
}
