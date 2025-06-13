<?php
/**
 * @package       WT Quick links
 * @copyright     Copyright (C) 2021-2025 Sergey Tolkachyov. All rights reserved.
 * @author        Sergey Tolkachyov
 * @link          https://web-tolk.ru
 * @version       2.4.0
 * @license       GNU General Public License version 2 or later
 */

namespace Joomla\Module\Wtquicklinks\Site\Helper;

use Exception;
use Joomla\CMS\Application\CMSApplicationInterface;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Application\SiteApplication;
use Joomla\CMS\Factory;
use Joomla\Component\Content\Site\Helper\RouteHelper;
use Joomla\Component\Jshopping\Site\Helper\Helper;
use Joomla\Module\Wtquicklinks\Site\Driver\AbstractDriver;
use Joomla\Module\Wtquicklinks\Site\Driver\DriverFactory;
use Joomla\Registry\Registry;

use function defined;
use function file_exists;
use function simplexml_load_file;
use function in_array;


defined('_JEXEC') or die;

/**
 * The helper class of a module
 *
 * @since  1.0
 */
class WtquicklinksHelper
{
    /**
     * Module params
     *
     * @var Registry
     * @since 2.4.0
     */
    protected Registry $params;
    /**
     * List of exclude rules from drivers
     *
     * @var array
     * @since 2.4.0
     */
    private array $exclude_rules = [];
    /**
     * List of drivers instances
     *
     * @var array
     * @since 2.4.0
     */
    private array $drivers = [];

    /**
     * Retrieve list of module entries
     *
     * @param   Registry  &$params  module parameters
     *
     * @return  object
     *
     * @since   4.2.0
     */
    public function getList(Registry $params, SiteApplication $app): object
    {
        $this->params = $params;

        $module_xml = simplexml_load_file(JPATH_SITE . '/modules/mod_wt_quick_links/mod_wt_quick_links.xml');
        $app->getDocument()->addScriptOptions('mod_wt_quick_links', [
            'name'         => Text::_((string)$module_xml->name),
            'type'         => (string)$module_xml['type'],
            'version'      => (string)$module_xml->version,
            'author'       => (string)$module_xml->author,
            'authorUrl'    => (string)$module_xml->authorUrl,
            'creationDate' => (string)$module_xml->creationDate,
        ]);

        $link_list = [];
        $link      = [];


        $excludeRules = $this->getExcludeRules();

        foreach ($params->get('fields') as $element)
        {
            $element = new Registry($element);
            if (!$linkDriver = $this->getDrivers()[$element->get('link_type')])
            {
                continue;
            }

            if ($element->get('exclude', 0) == 1 && $excludeRules[$element->get('exclude_type')]($element))
            {
                continue;
            }


            $link['link_text']            = $element->get('link_text');
            $link['link_image']           = $element->get('link_image');
            $link['link_icon_css']        = $element->get('link_icon_css');
            $link['link_additional_text'] = $element->get('link_additional_text');
            $link['is_responsive_images'] = $element->get('is_responsive_images', 0);
            $link['responsive_images']    = $element->get('responsive_images');
            $link['media_type']           = $element->get('media_type');
            $link['link_video']           = $element->get('link_video');
            $link['link_video_poster']    = $element->get('link_video_poster');
            $link['is_responsive_videos'] = $element->get('is_responsive_videos', 0);
            $link['responsive_videos']    = (array)$element->get('responsive_videos');
            $link['use_link']             = $element->get('use_link', 1);
            $link['onclick']              = $element->get('onclick', '');
            $link['sublayout']            = ($element->get('use_sublayout') == 1) ? $element->get('sublayout') : '';
            $link['url']                  = $linkDriver->setElement($element)->getLink();


//			/**
//			 * Условия исключения показа элемента
//			 */
//			if ($element->get('exclude', 0) == 1)
//			{
//				// Есть условия исключения показа
//
//				// Условие - категория JoomShopping
//
////				if ($element->get('exclude_type') == 'com_jshopping_categories'
////					&& $option == 'com_jshopping'
////					&& $input->get('controller') == 'category'
////					&& in_array($input->get('category_id'), (array) $element->get('exclude_jshoppingcategories'))
////				)
////				{
////					continue;
////				}
//
//				// Условие - категория Phoca Cart
//				if ($element->get('exclude_type') == 'com_phocacart_categories'
//					&& $option == 'com_phocacart'
//					&& (
//						(
//							$input->get('view') == 'category'
//							&& in_array($input->get('id'), (array) $element->get('exclude_phocacartcategories'))
//						)
//						|| (
//							$input->get('view') == 'item'
//							&& in_array($input->get('catid'), (array) $element->get('exclude_phocacartcategories'))
//						)
//					)
//				)
////				{
////					continue;
////				}
//
////				// Условие - категория Virtuemart
////
//				if ($element->get('exclude_type') == 'com_virtuemart_categories'
//					&& $option == 'com_virtuemart'
//					&& (
//						(
//							$input->get('view') == 'category'
//							&& in_array(
//								$input->get('virtuemart_category_id'),
//								(array) $element->get('exclude_virtuemartcategories')
//							)
//						)
//						|| (
//							$input->get('view') == 'productdetails'
//							&& in_array(
//								$input->get('virtuemart_category_id'),
//								(array) $element->get('exclude_virtuemartcategories')
//							)
//						)
//					)
//				)
//				{
//					continue;
//				}
////
////				// Условие - пункт меню
////
////				if ($element->get('exclude_type') == 'menuitem' && in_array(
////						$itemId,
////						(array) $element->get('exclude_menu_items')
////					))
////				{
////					continue;
////				}
////
////				// Условие - категория материалов Joomla
////
////				if ($element->get('exclude_type') == 'com_content_categories'
////					&& $option == 'com_content'
//					&& (
//						(
//							$input->get('view') == 'category'
//							&& in_array($input->get('id'), (array) $element->get('exclude_contentcategories'))
//						)
//						|| (
//							$input->get('view') == 'article'
//							&& in_array($input->get('catid'), (array) $element->get('exclude_contentcategories'))
//						)
//					)
////				)
////				{
////					continue;
////				}
////			}
//
//			if ($element->get('use_link', 1) == 1)
//			{
//				/**
//				 * Build links
//				 */
//				$link['url'] = '';
//
//				if ($element->get('link_type') == 'com_jshopping')
//				{
//					if (!empty($element->get('jshoppingcategories')))
//					{
//						if (!file_exists(JPATH_SITE . '/components/com_jshopping/bootstrap.php'))
//						{
//							continue;
//						}
//						require_once(JPATH_SITE . '/components/com_jshopping/bootstrap.php');
//						$jshop_link = 'index.php?option=com_jshopping&controller=category&task=view&category_id=' . $element->get(
//								'jshoppingcategories'
//							);
//
//						$jshop_itemid = Helper::getDefaultItemid($jshop_link);
//
//						$link['url'] = Route::_($jshop_link . '&Itemid=' . $jshop_itemid);
//					}
//				}
//				elseif ($element->get('link_type') == 'com_virtuemart')
//				{
//					// Avoid top level Virtuemart category with virtuemart_category_id=0
//					if ($element->get('virtuemartcategories') != '0')
//					{
//						$link['url'] = Route::_(
//							'index.php?option=com_virtuemart&view=category&virtuemart_category_id=' . $element->get(
//								'virtuemartcategories'
//							)
//						);
//					}
//				}
//				elseif ($element->get('link_type') == 'com_phocacart')
//				{
//					if (!empty($element->get('phocacartcategory')))
//					{
//						$link['url'] = Route::_(
//							'index.php?option=com_phocacart&view=category&id=' . $element->get('phocacartcategory')
//						);
//					}
//				}
//				elseif ($element->get('link_type') == 'com_content')
//				{
//					$link['url'] = Route::_(
//						'index.php?option=com_content&view=category&id=' . $element->get('contentcategories')
//					);
//				}
//				elseif ($element->get('link_type') == 'menuitem')
//				{
//
//				}
//				elseif ($element->get('link_type') == 'custom')
//				{
//					$link['url'] = $element->get('custom_link', '');
//				}
//				elseif ($element->get('link_type') == 'com_content_article')
//				{
//					if (!empty($element->get('com_content_article_id')))
//					{
//					}
//				}
//				elseif ($element->get('link_type') == 'file')
//				{
//					$link['url'] = $element->get('file_uri', '');
//				}
//			}

            $link_list[] = (object)$link;
        }

        return (object)$link_list;
    }

    private function getExcludeRules()
    {
        if (empty($this->exclude_rules))
        {
            $drivers = $this->getDrivers();

            foreach ($drivers as $driver)
            {
                if (!empty($exclude_type = $driver->getExcludeType()))
                {
                    $this->exclude_rules[$exclude_type] = $driver->getExcludeRule();
                }
            }
        }

        return $this->exclude_rules;
    }

    /**
     * Return a link drivers
     *
     * @return array
     *
     * @throws Exception
     * @since 1.4.0
     */
    public function getDrivers(): array
    {
        if (empty($this->drivers))
        {
            $drivers = DriverFactory::getDrivers($this->params);

            if (!$drivers)
            {
                throw new Exception('WT Quick links: Failed to load driver instances.');
            }

            $this->drivers = $drivers;
        }

        return $this->drivers;
    }
}