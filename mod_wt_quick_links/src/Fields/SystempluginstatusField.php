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

use Joomla\CMS\Form\Field\NoteField;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Plugin\PluginHelper;

class SystempluginstatusField extends NoteField
{

	protected $type = 'Systempluginstatus';

	/**
	 * @return  string  The field label markup.
	 *
	 * @since   1.7.0
	 */
	protected function getLabel()
	{
        if(!PluginHelper::isEnabled('system','wtquicklinks')) {
            $this->class = 'alert alert-danger w-100';
            $this->element['label'] = Text::_('MOD_WT_QUICK_LINKS_SYSTEMPLUGINSTATUS_FIELD_LABEL');
            $this->element['description'] = Text::_('MOD_WT_QUICK_LINKS_SYSTEMPLUGINSTATUS_FIELD_DESC');
            return parent::getLabel();
        }
        $this->parentclass = 'd-none';
        return '';

	}
}