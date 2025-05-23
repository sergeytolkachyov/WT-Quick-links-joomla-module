<?php
/**
 * @package       WT Quick links
 * @copyright   Copyright (C) 2021-2025 Sergey Tolkachyov. All rights reserved.
 * @author        Sergey Tolkachyov
 * @link          https://web-tolk.ru
 * @version     2.3.0
 * @license     GNU General Public License version 2 or later
 */

namespace Joomla\Module\Wtquicklinks\Site\Helper;

use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Application\SiteApplication;
use Joomla\CMS\Factory;
use Joomla\Component\Content\Site\Helper\RouteHelper;
use Joomla\Component\Jshopping\Site\Helper\Helper;
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
        $module_xml = simplexml_load_file(JPATH_SITE . '/modules/mod_wt_quick_links/mod_wt_quick_links.xml');
        $app->getDocument()->addScriptOptions('mod_wt_quick_links', [
            'name'         => Text::_((string)$module_xml->name),
            'type'         => (string)$module_xml['type'],
            'version'      => (string)$module_xml->version,
            'author'       => (string)$module_xml->author,
            'authorUrl'    => (string)$module_xml->authorUrl,
            'creationDate' => (string)$module_xml->creationDate,
        ]);

        $input = $app->getInput();

        $itemId    = $input->get('Itemid');
        $option    = $input->get('option');
        $link_list = [];
        $link      = [];

        foreach ($params->get('fields') as $field) {
            $field                        = new Registry($field);
            $link['link_text']            = $field->get('link_text');
            $link['link_image']           = $field->get('link_image');
            $link['link_icon_css']        = $field->get('link_icon_css');
            $link['link_additional_text'] = $field->get('link_additional_text');
            $link['is_responsive_images'] = $field->get('is_responsive_images', 0);
            $link['responsive_images']    = $field->get('responsive_images');
            $link['media_type']           = $field->get('media_type');
            $link['link_video']           = $field->get('link_video');
            $link['link_video_poster']    = $field->get('link_video_poster');
            $link['is_responsive_videos'] = $field->get('is_responsive_videos', 0);
            $link['responsive_videos']    = (array)$field->get('responsive_videos');
            $link['use_link']             = $field->get('use_link', 1);
            $link['onclick']              = $field->get('onclick', '');
            $link['sublayout'] = ($field->get('use_sublayout') == 1) ? $field->get('sublayout') : '';

            /**
             * Условия исключения показа элемента
             */
            if ($field->get('exclude', 0) == 1) {
                // Есть условия исключения показа

                // Условие - категория JoomShopping

                if ($field->get('exclude_type') == 'com_jshopping_categories'
                    && $option == 'com_jshopping'
                    && $input->get('controller') == 'category'
                    && in_array($input->get('category_id'), (array)$field->get('exclude_jshoppingcategories'))
                ) {
                    continue;
                }

                // Условие - категория Phoca Cart
                if ($field->get('exclude_type') == 'com_phocacart_categories'
                    && $option == 'com_phocacart'
                    && (
                        (
                            $input->get('view') == 'category'
                            && in_array($input->get('id'), (array)$field->get('exclude_phocacartcategories'))
                        )
                        || (
                            $input->get('view') == 'item'
                            && in_array($input->get('catid'), (array)$field->get('exclude_phocacartcategories'))
                        )
                    )
                ) {
                    continue;
                }

                // Условие - категория Virtuemart

                if ($field->get('exclude_type') == 'com_virtuemart_categories'
                    && $option == 'com_virtuemart'
                    && (
                        (
                            $input->get('view') == 'category'
                            && in_array(
                                $input->get('virtuemart_category_id'),
                                (array)$field->get('exclude_virtuemartcategories')
                            )
                        )
                        || (
                            $input->get('view') == 'productdetails'
                            && in_array(
                                $input->get('virtuemart_category_id'),
                                (array)$field->get('exclude_virtuemartcategories')
                            )
                        )
                    )
                ) {
                    continue;
                }

                // Условие - пункт меню

                if ($field->get('exclude_type') == 'menuitem' && in_array(
                        $itemId,
                        (array)$field->get('exclude_menu_items')
                    )) {
                    continue;
                }

                // Условие - категория материалов Joomla

                if ($field->get('exclude_type') == 'com_content_categories'
                    && $option == 'com_content'
                    && (
                        (
                            $input->get('view') == 'category'
                            && in_array($input->get('id'), (array)$field->get('exclude_contentcategories'))
                        )
                        || (
                            $input->get('view') == 'article'
                            && in_array($input->get('catid'), (array)$field->get('exclude_contentcategories'))
                        )
                    )
                ) {
                    continue;
                }
            }

            if ($field->get('use_link', 1) == 1) {
                /**
                 * Build links
                 */
                $link['url'] = '';

                if ($field->get('link_type') == 'com_jshopping') {
                    if (!empty($field->get('jshoppingcategories'))) {
                        if (!file_exists(JPATH_SITE . '/components/com_jshopping/bootstrap.php')) {
                            continue;
                        }
                        require_once(JPATH_SITE . '/components/com_jshopping/bootstrap.php');
                        $jshop_link = 'index.php?option=com_jshopping&controller=category&task=view&category_id=' . $field->get(
                                'jshoppingcategories'
                            );

                        $jshop_itemid = Helper::getDefaultItemid($jshop_link);

                        $link['url'] = Route::_($jshop_link . '&Itemid=' . $jshop_itemid);
                    }
                } elseif ($field->get('link_type') == 'com_virtuemart') {
                    // Avoid top level Virtuemart category with virtuemart_category_id=0
                    if ($field->get('virtuemartcategories') != '0') {
                        $link['url'] = Route::_(
                            'index.php?option=com_virtuemart&view=category&virtuemart_category_id=' . $field->get(
                                'virtuemartcategories'
                            )
                        );
                    }
                } elseif ($field->get('link_type') == 'com_phocacart') {
                    if (!empty($field->get('phocacartcategory'))) {
                        $link['url'] = Route::_(
                            'index.php?option=com_phocacart&view=category&id=' . $field->get('phocacartcategory')
                        );
                    }
                } elseif ($field->get('link_type') == 'com_content') {
                    $link['url'] = Route::_(
                        'index.php?option=com_content&view=category&id=' . $field->get('contentcategories')
                    );
                } elseif ($field->get('link_type') == 'menuitem') {
                    $menu_item   = $app->getMenu('site')->getItem($field->get('menuitem'));
                    $link['url'] = Route::_($menu_item->link . '&Itemid=' . $menu_item->id);
                } elseif ($field->get('link_type') == 'custom') {
                    $link['url'] = $field->get('custom_link', '');
                } elseif ($field->get('link_type') == 'com_content_article') {
                    if (!empty($field->get('com_content_article_id'))) {
                        $model = $app->bootComponent('com_content')
                            ->getMVCFactory()
                            ->createModel('Article', 'Site', ['ignore_request' => true]);
                        // Set application parameters in model
                        $model->setState('params', $app->getParams());
                        $article = $model->getItem($field->get('com_content_article_id'));

                        $link['url'] = Route::link(
                            'site',
                            RouteHelper::getArticleRoute(
                                $field->get('com_content_article_id'),
                                $article->catid,
                                $article->language
                            )
                        );
                    }
                } elseif ($field->get('link_type') == 'file') {
                    $link['url'] = $field->get('file_uri', '');
                }
            }

            $link_list[] = (object)$link;
        }

        return (object)$link_list;
    }
}