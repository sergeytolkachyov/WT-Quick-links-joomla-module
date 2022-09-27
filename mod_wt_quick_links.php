<?php
/**
 * @package     Wt Quick Links
 * @copyright   Copyright (C) 2022 Sergey Tolkachyov. All rights reserved.
 * @link 		https://web-tolk.ru
 * @version 	1.3.0
 * @license     GNU General Public License version 2 or later
 */
use Joomla\CMS\Factory;
use Joomla\CMS\Helper\ModuleHelper;
use Joomla\CMS\Version;

defined('_JEXEC') or die;

// Include the archive functions only once
JLoader::register('ModWTQuickLinks', __DIR__ . '/helper.php');
$list            = ModWTQuickLinks::getList($params);
$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'), ENT_COMPAT, 'UTF-8');
/*
 * Take a css file for tmpl with the same name form media folder
 */
if(!empty($params->get('layout')))
{
	$doc = Factory::getApplication()->getDocument();
	$tmpl_css = explode(':', $params->get('layout'));
	$tmpl_css_file = $tmpl_css[1];
	if(file_exists('media/mod_wt_quick_links/css/'.$tmpl_css_file.'.css')){
		if((new Version)->isCompatible('4.0')){
			// joomla 4
			$wa = $doc->getWebAssetManager();
			$wa->registerAndUseStyle('mod.wtquicklinks.'.$tmpl_css_file, 'media/mod_wt_quick_links/css/'.$tmpl_css_file.'.css');
		} else {
			// Joomla 3
			$doc->addStyleSheet('media/mod_wt_quick_links/css/'.$tmpl_css_file.'.css');
		}
		
	}
}

require ModuleHelper::getLayoutPath('mod_wt_quick_links', $params->get('layout', 'default'));
