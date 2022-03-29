<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_wt_quick_links
 *
 * @copyright   Copyright (C) 2021 Sergey Tolkachyov. All rights reserved.
 * @version 1.1.0
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
use Joomla\CMS\Factory;
use Joomla\CMS\Helper\ModuleHelper;

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
	$doc = Factory::getDocument();
	$tmpl_css = explode(':', $params->get('layout'));
	$tmpl_css_file = $tmpl_css[1];
	if(file_exists('media/mod_wt_quick_links/css/'.$tmpl_css_file.'.css')){
		$doc->addStyleSheet('media/mod_wt_quick_links/css/'.$tmpl_css_file.'.css');
	}
}

require ModuleHelper::getLayoutPath('mod_wt_quick_links', $params->get('layout', 'default'));
