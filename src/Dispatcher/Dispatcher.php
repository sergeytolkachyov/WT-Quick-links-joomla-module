<?php
/**
 * @package     Wt Quick Links
 * @copyright   Copyright (C) 2021-2023 Sergey Tolkachyov. All rights reserved.
 * @author      Sergey Tolkachyov - https://web-tolk.ru
 * @link 		https://web-tolk.ru
 * @version 	2.1.1
 * @license     GNU General Public License version 2 or later
 */

namespace Joomla\Module\Wtquicklinks\Site\Dispatcher;

\defined('JPATH_PLATFORM') or die;

use Exception;
use Joomla\CMS\Dispatcher\AbstractModuleDispatcher;
use Joomla\CMS\Helper\HelperFactoryAwareInterface;
use Joomla\CMS\Helper\HelperFactoryAwareTrait;

/**
 * Dispatcher class for mod_wt_quick_links
 *
 * @since  4.2.0
 */
class Dispatcher extends AbstractModuleDispatcher implements HelperFactoryAwareInterface
{
	use HelperFactoryAwareTrait;

	/**
	 * Returns the layout data.
	 *
	 * @return  array
	 *
	 * @since   4.2.0
	 */
	protected function getLayoutData() : array
	{

		$data = parent::getLayoutData();
		$data['list'] = $this->getHelperFactory()->getHelper('WtquicklinksHelper')->getList($data['params'], $this->getApplication());
		
		if(!empty($data['params']->get('moduleclass_sfx'))){
			 $data['moduleclass_sfx']  = htmlspecialchars($data['params']->get('moduleclass_sfx'), ENT_COMPAT, 'UTF-8');
		}

		$wa = $this->getApplication()->getDocument()->getWebAssetManager();
		$tmpl_css = explode(':', $data['params']->get('layout'));
		$tmpl_css_file = $tmpl_css[1];
		if(file_exists('media/mod_wt_quick_links/css/'.$tmpl_css_file.'.css')){
			$wa->registerAndUseStyle('mod.wtquicklinks.'.$tmpl_css_file, 'media/mod_wt_quick_links/css/'.$tmpl_css_file.'.css');
		}

		return $data;
	}
}