<?php
/**
 * @package       WT Quick links
 * @copyright     Copyright (C) 2021-2025 Sergey Tolkachyov. All rights reserved.
 * @author        Sergey Tolkachyov
 * @link          https://web-tolk.ru
 * @version       2.4.0.1
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
            $link['url']                  = ($element->get('use_link', 1) == 1) ? $linkDriver->setElement($element)->getLink() : '';

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