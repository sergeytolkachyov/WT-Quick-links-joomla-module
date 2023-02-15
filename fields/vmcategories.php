<?php
defined('_JEXEC') or die();

/**
 *
 * @package    VirtueMart
 * @subpackage Plugins  - Elements
 * @author Valérie Isaksen
 * @link https://virtuemart.net
 * @copyright Copyright (c) 2004 - 2011 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id$
 */


/*
 * This element is used by the menu manager
 * Should be that way
 */
class JFormFieldVmcategories extends JFormField {

	var $type = 'vmcategories';

	protected function getInput() {

		if(!file_exists(JPATH_ROOT .'/administrator/components/com_virtuemart/helpers/config.php')){
			return '<span class="badge badge-danger bg-danger">-- Virtuemart component is not installled --</span>';
		}


		if (!class_exists( 'VmConfig' )) require(JPATH_ROOT .'/administrator/components/com_virtuemart/helpers/config.php');
		VmConfig::loadConfig();
		vmLanguage::loadJLang('com_virtuemart');

		if(!is_array($this->value)){
			if(strpos($this->value,',')!==FALSE){
				$this->value = explode(',',$this->value);
			} else {
				$this->value = array($this->value);
			}
		}

		$categorylist = ShopFunctions::categoryListTree($this->value);
		$name = $this->name;

		$id = VmHtml::ensureUniqueId('vmcategories');
		$html = '<select id="'.$id.'" class="form-select" name="' . $name . '">';
		if(!$this->multiple)$html .= '<option value="0">' . vmText::_('COM_VIRTUEMART_CATEGORY_FORM_TOP_LEVEL') . '</option>';
		$html .= $categorylist;
		$html .= "</select>";
		return $html;
	}


}


