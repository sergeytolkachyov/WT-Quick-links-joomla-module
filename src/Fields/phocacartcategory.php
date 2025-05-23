<?php
/**
 * @package       WT Quick links
 * @copyright   Copyright (C) 2021-2025 Sergey Tolkachyov. All rights reserved.
 * @author        Sergey Tolkachyov
 * @link          https://web-tolk.ru
 * @version     2.3.0
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die();

use Joomla\CMS\Factory;
use Joomla\CMS\Form\Field\ListField;
use Joomla\CMS\Language\Text;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\Database\DatabaseInterface;

class JFormFieldPhocacartCategory extends ListField
{
    protected $type = 'PhocacartCategory';

    protected function getInput()
    {
        if (!file_exists(JPATH_ADMINISTRATOR . '/components/com_phocacart/libraries/phocacart/category/category.php')) {
            return '<span class="badge badge-danger bg-danger">-- Phoca Cart component is not installled --</span>';
        }

        if (!class_exists('PhocacartCategory')) {
            require_once(JPATH_ADMINISTRATOR . '/components/com_phocacart/libraries/phocacart/category/category.php');
        }
        if (!class_exists('PhocacartCategoryMultiple')) {
            require_once(JPATH_ADMINISTRATOR . '/components/com_phocacart/libraries/phocacart/category/multiple.php');
        }

        $lang = Factory::getApplication()->getLanguage();
        $lang->load('com_phocacart');

        $db = Factory::getContainer()->get(DatabaseInterface::class);

        //$javascript		= '';
        $required     = ((string)$this->element['required'] == 'true') ? true : false;
        $multiple     = ((string)$this->element['multiple'] == 'true') ? true : false;
        $class        = ((string)$this->element['class'] != '') ? 'class="' . $this->element['class'] . '"' : 'class="form-select"';
        $typeMethod   = $this->element['typemethod'];
        $categoryType = $this->element['categorytype'];// 0 all, 1 ... online shop, 2 ... pos
        $attr         = $class . ' ';
        if ($multiple) {
            $attr .= 'size="4" multiple="multiple" ';
        }
        if ($required) {
            $attr .= 'required aria-required="true" ';
        }

        $attr .= $this->element['onchange'] ? ' onchange="' . (string)$this->element['onchange'] . '" ' : ' ';
        //$attr .= $javascript . ' ';


        // Multiple load more values
        $activeCats = [];
        $id         = 0;
        // Active cats can be selected in administration item view
        // but this function is even called in module so ignore this part for module administration
        if ($multiple && $this->form->getName() == 'com_phocacart.phocacartitem') {
            $id = (int)$this->form->getValue('id');// Product ID
            if ($id > 0) {
                $activeCats = PhocacartCategoryMultiple::getCategories($id, 1);
            }
        }


        // Filter language
        //$whereLang = '';
        $wheres = [];
        if (!empty($this->element['language'])) {
            if (strpos($this->element['language'], ',') !== false) {
                $language = implode(',', $db->quote(explode(',', $this->element['language'])));
            } else {
                $language = $db->quote($this->element['language']);
            }

            $wheres[] = ' ' . $db->quoteName('a.language') . ' IN (' . $language . ')';
        }


        //build the list of categories
        $query = 'SELECT a.title AS text, a.id AS value, a.parent_id as parentid'
            . ' FROM #__phocacart_categories AS a';

        // don't lose information about category when it will be unpublished - you should still be able to edit product with such category in administration
        //. ' WHERE a.published = 1';
        switch ($categoryType) {
            case 1:
                $wheres[] = ' a.type IN (0,1)';
                break;
            case 2:
                $wheres[] = ' a.type IN (0,2)';
                break;
            case 0:
            default:
                break;
        }

        $query .= (count($wheres) ? ' WHERE ' . implode(' AND ', $wheres) : '');


        $query .= ' ORDER BY a.ordering';

        $db->setQuery($query);
        $data = $db->loadObjectList();

        // TO DO - check for other views than category edit
        $view  = Factory::getApplication()->getInput()->get('view');
        $catId = -1;
        if ($view == 'phocacartcategory') {
            $id = $this->form->getValue('id'); // id of current category
            if ((int)$id > 0) {
                $catId = $id;
            }
        }


        $tree = [];
        $text = '';
        $tree = PhocacartCategory::CategoryTreeOption($data, $tree, 0, $text, $catId);

        if ($multiple) {
            if ($typeMethod == 'allnone') {
                array_unshift(
                    $tree,
                    HTMLHelper::_('select.option', '0', Text::_('COM_PHOCACART_NONE'), 'value', 'text')
                );
                array_unshift(
                    $tree,
                    HTMLHelper::_('select.option', '-1', Text::_('COM_PHOCACART_ALL'), 'value', 'text')
                );
            }
        } else {
            // in filter we need zero value for canceling the filter
            if ($typeMethod == 'filter') {
                array_unshift(
                    $tree,
                    HTMLHelper::_(
                        'select.option',
                        '',
                        '- ' . Text::_('COM_PHOCACART_SELECT_CATEGORY') . ' -',
                        'value',
                        'text'
                    )
                );
            } else {
                array_unshift(
                    $tree,
                    HTMLHelper::_(
                        'select.option',
                        '0',
                        '- ' . Text::_('COM_PHOCACART_SELECT_CATEGORY') . ' -',
                        'value',
                        'text'
                    )
                );
            }
        }


        $data            = $this->getLayoutData();
        $data['options'] = (array)$tree;

        if (!empty($activeCats)) {
            $data['value'] = $activeCats;
        } else {
            $data['value'] = $this->value;
        }

        return $this->getRenderer($this->layout)->render($data);
    }
}