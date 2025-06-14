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

use Joomla\CMS\Editor\Editor;
use Joomla\CMS\Form\Field\EditorField;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;

defined('_JEXEC') or die;

/**
 * A textarea field for content creation
 *
 * @see    JEditor
 * @since  1.6
 */
class WrappededitorField extends EditorField
{
    /**
     * The form field type.
     *
     * @var    string
     * @since  1.6
     */
    public $type = 'Wrappededitor';

    /**
     * Method to get the field input markup for the editor area
     *
     * @return  string  The field input markup.
     *
     * @since   1.6
     */
    protected function getInput()
    {
        $wrapped_editor = [];
        $wrapped_editor[] = HTMLHelper::_('bootstrap.startAccordion', 'accordion'.$this->id, []);
        $accordion_title = Text::_('MOD_WT_QUICK_LINKS_EDITOR_FIELD_LABEL');

        $wrapped_editor[] = HTMLHelper::_('bootstrap.addSlide','accordion'.$this->id, $accordion_title,'accordionSlide-'.$this->id);
        $wrapped_editor[] = parent::getInput();
        $wrapped_editor[] = HTMLHelper::_('bootstrap.endSlide');
        $wrapped_editor[] = HTMLHelper::_('bootstrap.endAccordion');

        return implode('', $wrapped_editor);
    }
}
