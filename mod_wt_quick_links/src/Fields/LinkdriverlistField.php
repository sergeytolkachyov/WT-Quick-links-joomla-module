<?php
/**
 * @package       WT Quick links
 * @copyright   Copyright (C) 2021-2025 Sergey Tolkachyov. All rights reserved.
 * @author        Sergey Tolkachyov
 * @link          https://web-tolk.ru
 * @version 	2.4.0
 * @license     GNU General Public License version 2 or later
 */

namespace Joomla\Module\Wtquicklinks\Site\Fields;

defined('_JEXEC') or die;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Form\Field\ListField;
use Joomla\Module\Wtquicklinks\Site\Driver\DriverFactory;
use Joomla\Registry\Registry;

class LinkdriverlistField extends ListField
{

    protected $type = 'Linkdriverlist';

    protected function getOptions()
    {
        $options = [];
        $drivers = DriverFactory::getDrivers((new Registry()));

        foreach ($drivers as $driver)
        {
            $attrs = [];
            if(!empty($component = $driver->getComponent()) && !ComponentHelper::isEnabled($component)) {
                $attrs['disable'] = true;
            }

            $options[] = HTMLHelper::_('select.option', $driver->getLinkType(), $driver->getLinkTypeLabel(), $attrs);
        }

        return $options;
    }
}