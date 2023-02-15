<?php
defined('_JEXEC') or die();

use Joomla\CMS\Form\FormHelper;
use Joomla\CMS\HTML\HTMLHelper;

FormHelper::loadFieldClass('list');

class JFormFieldVmcategories extends JFormFieldList
{

	protected $type = 'vmcategories';

	protected function getOptions()
	{

		if (!file_exists(JPATH_ROOT . '/administrator/components/com_virtuemart/helpers/config.php'))
		{
			return '<span class="badge badge-danger bg-danger">-- Virtuemart component is not installled --</span>';
		}

		$options = array();
		if (!class_exists('VmConfig')) require(JPATH_ROOT . '/administrator/components/com_virtuemart/helpers/config.php');
		VmConfig::loadConfig();
		vmLanguage::loadJLang('com_virtuemart');

		$cats = VirtueMartModelCategory::getCatsTree(false, '', 0, 10, false, VmConfig::isSite());

		$options = array();
		if (!empty($cats))
		{
			foreach ($cats as $key => $category)
			{

				$categoryTree = '';
				if ($category->level >= 1)
				{
					$categoryTree .= str_repeat(' - ', ($category->level));
				}
				$categoryTree .= $category->category_name;
				$options[]    = HTMLHelper::_('select.option', $category->virtuemart_category_id, $categoryTree);

			}
		}

		return $options;
	}


}


