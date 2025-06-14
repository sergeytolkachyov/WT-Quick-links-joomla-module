<?php
/**
 * @package       WT Quick links
 * @copyright   Copyright (C) 2021-2025 Sergey Tolkachyov. All rights reserved.
 * @author        Sergey Tolkachyov
 * @link          https://web-tolk.ru
 * @version 	2.4.0.1
 * @license     GNU General Public License version 2 or later
 */

namespace Joomla\Module\Wtquicklinks\Site\Fields;

defined('_JEXEC') or die;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Form\Field\ListField;
use Joomla\Module\Wtquicklinks\Site\Driver\DriverFactory;
use Joomla\Registry\Registry;

class ExcludelinkdriverlistField extends ListField
{

    protected $type = 'Excludelinkdriverlist';

    protected array $remove_non_excludable_drivers = ['custom', 'file'];

    protected function getOptions()
    {
        $options = [];

        $drivers = DriverFactory::getDrivers((new Registry()));

        foreach ($drivers as $driver)
        {
            if(in_array($driver->getLinkType(), $this->remove_non_excludable_drivers)) {
                continue;
            }

            $attrs = [];
            if(!empty($component = $driver->getComponent()) && !ComponentHelper::isEnabled($component)) {
                $attrs['disable'] = true;
            }

            $options[] = HTMLHelper::_('select.option', $driver->getExcludeType(), $driver->getLinkTypeLabel(), $attrs);

        }

        return $options;
    }
}