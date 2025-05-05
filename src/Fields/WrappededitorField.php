<?php
/**
 * @package     WT Quick Links
 * @copyright   Copyright (C) 2021-2025 Sergey Tolkachyov. All rights reserved.
 * @author      Sergey Tolkachyov - https://web-tolk.ru
 * @link 		https://web-tolk.ru
 * @version 	2.2.1
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
     * The Editor object.
     *
     * @var    Editor
     * @since  1.6
     */
    protected $editor;

    /**
     * The height of the editor.
     *
     * @var    string
     * @since  3.2
     */
    protected $height;

    /**
     * The width of the editor.
     *
     * @var    string
     * @since  3.2
     */
    protected $width;

    /**
     * The assetField of the editor.
     *
     * @var    string
     * @since  3.2
     */
    protected $assetField;

    /**
     * The authorField of the editor.
     *
     * @var    string
     * @since  3.2
     */
    protected $authorField;

    /**
     * The asset of the editor.
     *
     * @var    string
     * @since  3.2
     */
    protected $asset;

    /**
     * The buttons of the editor.
     *
     * @var    mixed
     * @since  3.2
     */
    protected $buttons;

    /**
     * The hide of the editor.
     *
     * @var    string[]
     * @since  3.2
     */
    protected $hide;

    /**
     * The editorType of the editor.
     *
     * @var    string[]
     * @since  3.2
     */
    protected $editorType;

    /**
     * The parent class of the field
     *
     * @var  string
     * @since 4.0.0
     */
    protected $parentclass;


    /**
     * Method to get the field input markup for the editor area
     *
     * @return  string  The field input markup.
     *
     * @since   1.6
     */
    protected function getInput()
    {
        // Get an editor object.
        $editor = $this->getEditor();
        $params = [
            'autofocus' => $this->autofocus,
            'readonly'  => $this->readonly || $this->disabled,
            'syntax'    => (string) $this->element['syntax'],
        ];

		$wrapped_editor = [];
	    $rand_id = rand(1,1000);
	    $wrapped_editor[] = HTMLHelper::_('bootstrap.startAccordion', 'accordion'.$rand_id, []);
	    $accordion_title = Text::_('MOD_WT_QUICK_LINKS_EDITOR_FIELD_LABEL');;

	    $wrapped_editor[] = HTMLHelper::_('bootstrap.addSlide','accordion'.$rand_id, $accordion_title,'accordionSlide-'.$rand_id);
        $wrapped_editor[] = $editor->display(
            $this->name,
            htmlspecialchars($this->value, ENT_COMPAT, 'UTF-8'),
            $this->width,
            $this->height,
            $this->columns,
            $this->rows,
            $this->buttons ? (\is_array($this->buttons) ? array_merge($this->buttons, $this->hide) : $this->hide) : false,
            $this->id,
            $this->asset,
            $this->form->getValue($this->authorField),
            $params
        );
	    $wrapped_editor[] = HTMLHelper::_('bootstrap.endSlide');
	    $wrapped_editor[] = HTMLHelper::_('bootstrap.endAccordion');

	    return implode('', $wrapped_editor);
    }
}
