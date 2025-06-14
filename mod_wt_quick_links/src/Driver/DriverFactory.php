<?php
/**
 * @package       WT Quick links
 * @copyright     Copyright (C) 2021-2025 Sergey Tolkachyov. All rights reserved.
 * @author        Sergey Tolkachyov
 * @link          https://web-tolk.ru
 * @version       2.4.0.1
 * @license       GNU General Public License version 2 or later
 */

namespace Joomla\Module\Wtquicklinks\Site\Driver;

use Joomla\CMS\Application\CMSApplicationInterface;
use Joomla\Filesystem\File;
use Joomla\Filesystem\Folder;
use Joomla\Registry\Registry;

// No direct access to this file
defined('_JEXEC') or die;

class DriverFactory
{
	/**
	 * Method to create a driver instance
	 *
	 * @param string $context The context of driver
	 * @param Registry $params Current WT Yandex map items instance module params
     * @param CMSApplicationInterface $app Current application instance
	 *
	 * @return []|bool Driver instance on success or false on failure
     *
	 * @since 2.4.0
	 */
	public static function getDrivers(Registry $params): array
	{

        $files = Folder::files(JPATH_SITE. '/modules/mod_wt_quick_links/src/Driver/Collection', '.php');
        if(empty($files)) {
            return [];
        }

		$drivers = [];
		foreach ($files as $file) {
			$file = File::stripExt($file);
			$class_name = __NAMESPACE__ . '\\Collection\\' . ucfirst($file);

			if (!class_exists($class_name))
			{
				continue;
			}
			$driver = new $class_name($params);
			$drivers[$driver->getLinkType()] = $driver;
		}

		return $drivers;
	}
}
